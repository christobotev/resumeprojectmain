<?php
namespace Docs\MainBundle\DataProvider;

use Docs\CommonBundle\Repository\AppointmentRepository;
use Knp\Component\Pager\Paginator;
use Docs\CommonBundle\Entity\Appointment;
use Symfony\Component\HttpFoundation\Request;
use Docs\MainBundle\DataProvider\Filter\FilterManager;
use Docs\AuthBundle\Security\Authentication\SecurityUser;
use Doctrine\ORM\QueryBuilder;

/**
 * Class that manages the retrieval
 * of appointments
 * @author hbotev
 *
 */
class AppointmentsProvider implements DataProviderInterface
{
    /**
     * Default items per page
     */
    const ITEMS_PER_PAGE = 10;

    /**
     * @var AppointmentRepository
     */
    protected $appointmentsRepo;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var FilterManager
     */
    protected $filterManager;

    /**
     * @param AppointmentRepository $appointmentsRepo
     * @param Paginator $paginator
     * @param FilterManager $filterManager
     */
    public function __construct(
        AppointmentRepository $appointmentsRepo,
        Paginator $paginator,
        FilterManager $filterManager
    ) {
        $this->appointmentsRepo = $appointmentsRepo;
        $this->paginator = $paginator;
        $this->filterManager = $filterManager;
    }

    /**
     * @param Request $request
     * @param SecurityUser|NULL $user
     * @return string[]|\Knp\Component\Pager\Pagination\PaginationInterface[]
     */
    public function getAppointments(Request $request, $user = null)
    {
        $queryBuilder = $this->appointmentsRepo->cloneQueryBuilder();
        /* @var $queryBuilder \Doctrine\ORM\QueryBuilder */

        $this->filterManager->startChain($queryBuilder, $request)
                            ->filter('appointmentStatus')
                            ->filter('appointmentDate');

        if ($user instanceof SecurityUser) {
            $this->filterUserSpecific($user, $queryBuilder);
        }

        if ($request->query->has("export")) {
            $pagination = '';
            // set limit on the request
            $queryBuilder->setFirstResult($request->get('from'))
                         ->setMaxResults($request->get('step'));

            $appointments = $queryBuilder->getQuery()->getResult();
        } else {
            $pagination = $this->paginator->paginate(
                $queryBuilder,
                $request->get("page", 1),
                $request->get("perPage", self::ITEMS_PER_PAGE)
            );

            $appointments = $pagination->getItems();
        }

        $appsData = [];
        foreach ($appointments as $key => $appointment) {
            $creator = $appointment->getUser();
            $user = $appointment->getWithUser();
            $note = $appointment->getNote();
            $appsData[$key]['appointmentID'] = $appointment->getAppointmentID();
            $appsData[$key]['user'] = $user->getFirstName() .' ' . $user->getLastName();
            $appsData[$key]['creator'] = $creator->getFirstName() .' ' . $creator->getLastName();
            $appsData[$key]['created'] = $appointment->getCreated();
            $appsData[$key]['scheduled'] = $appointment->getScheduled();
            $appsData[$key]['note'] = $note ? $note->getContent() : '';
            $appsData[$key]['status'] = $appointment->getStatus();
        }

        return [
         "appointments" => $appsData,
         "pagination" => $pagination
        ];
    }

    /**
     * MD's see appointments with them
     * other users see their own appointmens
     * @param SecurityUser $user
     * @param QueryBuilder $queryBuilder
     */
    protected function filterUserSpecific(SecurityUser $user, QueryBuilder $queryBuilder)
    {
        if ($user->isDoctor()) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq("Appointment.withUser", ":user"))
                            ->setParameter(":user", $user->getUserID());
            return;
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq("Appointment.user", ":user"))
                     ->setParameter(":user", $user->getUserID());
    }
}
