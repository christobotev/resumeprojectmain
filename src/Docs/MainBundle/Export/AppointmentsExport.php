<?php
namespace Docs\MainBundle\Export;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Docs\MainBundle\DataProvider\AppointmentsProvider;
use Docs\AuthBundle\Security\Authentication\SecurityUser;

/**
 * Class responsible for the generation
 * of exports for the "Appointments" grid
 *
 */
class AppointmentsExport
{
    /**
     * The offset for the next db query
     * @var int
     */
    protected $batchFrom = 0;

    /**
     * The number of appointments to retrieve on one batch
     * @var int
     */
    protected $step = 100;

    /**
     * @var AppointmentsProvider
     */
    protected $dataProvider;

    /**
     * Exports route
     * @var string
     */
    protected $basePath;

    /**
     * The entity manager is needed to clear the managed entities
     * from the unit of work, to prevent memory leaks
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @param string $basePath - %kernel.root_dir%/Resources/exports
     * @param AllData $dataProvider
     * @param EntityManager $entityManager
     */
    public function __construct(
        $basePath,
        AppointmentsProvider $dataProvider,
        EntityManager $entityManager
    ) {
        $this->dataProvider = $dataProvider;
        $this->basePath = $basePath;
        $this->entityManager = $entityManager;
    }

    /**
     * Generate the report and save it to a file
     * in app/Resources/exports
     * @param Request $request
     * @param SecurityUser|NULL $user
     * @return string
     */
    public function generate(Request $request, $user = null)
    {
        $filePath = $this->basePath . '/' . uniqid('apps_tmp_') . '.csv';
        $handle = fopen($filePath, 'w+');

        // Add the header of the CSV file
        fputcsv(
            $handle,
            [
                'Doctor',
                'Patient',
                'Created',
                'Scheduled',
                'Note',
                'Status'
            ]
        );

        while ($data = $this->getAppointments($request, $user)) {
            foreach ($data as $appointment) {
                fputcsv(
                    $handle,
                    [$appointment['user'],
                    $appointment['creator'],
                    $appointment['created']->format('Y-m-d H:i:s'),
                    $appointment['scheduled']->format('Y-m-d H:i:s'),
                    $appointment['status']->getName(),
                    $appointment['note']]
                );
            }

            // free the resources
            unset($data);
            // clear the unit of work
            $this->entityManager->clear();
            gc_collect_cycles();
        }

        fclose($handle);
        return $filePath;
    }

    /**
     * Getter for report file
     * full path
     * @return string
     */
    public function getFile()
    {
        return $this->filePath;
    }

    /**
     * Get appointments, 100 at a time
     * @param Request $request
     * @param SecurityUser|Null $user
     * @return boolean|array
     */
    protected function getAppointments(Request $request, $user)
    {
        $request->query->set('from', $this->batchFrom);
        $request->query->set('step', $this->step);

        $data = $this->dataProvider
                                ->getAppointments($request, $user);

        if (empty($data['appointments'])) {
            return false;
        }

        $this->batchFrom += $this->step;
        return $data['appointments'];
    }
}
