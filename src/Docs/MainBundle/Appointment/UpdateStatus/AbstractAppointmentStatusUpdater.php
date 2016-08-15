<?php
namespace Docs\MainBundle\Appointment\UpdateStatus;

use Docs\CommonBundle\Repository\AppointmentRepository;
use Docs\MainBundle\Reference\ReferenceFactory;
use Docs\CommonBundle\Entity\AppointmentStatus;

abstract class AbstractAppointmentStatusUpdater
{
    /**
     * @var AppointmentRepository
     */
    protected $appointmentRepo;

    /**
     * @var ReferenceFactory
     */
    protected $referenceFactory;

    /**
     * @param AppointmentRepository $appRepo
     * @param ReferenceFactory $refFactory
     */
    public function __construct(
        AppointmentRepository $appRepo,
        ReferenceFactory $refFactory
    ) {
        $this->appointmentRepo = $appRepo;
        $this->referenceFactory = $refFactory;
    }

    /**
     * Changes the appointment status
     * @param string $appID
     * @param int $newStatus
     */
    public function changeStatus($appID, $newStatus)
    {
        $appData = $this->getAppInfo($appID);

        $status = $this->referenceFactory
                            ->getReference(
                                AppointmentStatus::class,
                                $newStatus
                            );

        $appData->setStatus($status);

        $this->appointmentRepo->save($appData);
    }

    /**
     * Get appointment data
     * @param string $appID
     */
    protected function getAppInfo($appID)
    {
        return $this->appointmentRepo
                                ->find($appID);
    }
}
