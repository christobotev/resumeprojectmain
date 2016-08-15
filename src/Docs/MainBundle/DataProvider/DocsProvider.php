<?php
namespace Docs\MainBundle\DataProvider;

use Knp\Component\Pager\PaginatorInterface as Paginator;
use Symfony\Component\HttpFoundation\Request;
use Docs\CommonBundle\Entity\Appointment;
use Docs\CommonBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Docs\CommonBundle\Entity\Role;
use Docs\CommonBundle\Repository\AppointmentRepository;
use Docs\CommonBundle\Repository\UserRepository;

class DocsProvider implements DataProviderInterface
{
    /**
     * Default items per page
     */
    const ITEMS_PER_PAGE = 10;

    /**
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    protected $paginator;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var AppointmentRepository
     */
    protected $appointmentsRepo;

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * @param EntityManager $em
     * @param Paginator $paginator
     */
    public function __construct(
        EntityManager $em,
        Paginator $paginator,
        AppointmentRepository $appointmentsRepo,
        UserRepository $userRepo
    ) {
        $this->paginator = $paginator;
        $this->entityManager = $em;
        $this->appointmentsRepo = $appointmentsRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Get all doctors with less than
     * 3 open appointments
     * @param Request $request
     */
    public function getAvailableDocs(Request $request)
    {
        $subQueryBuilder = $this->entityManager
                                    ->createQueryBuilder();

        $subQueryBuilder->select("IDENTITY(App.withUser)")
                        ->from("\Docs\CommonBundle\Entity\Appointment", "App")
                        ->where($subQueryBuilder->expr()->eq('App.status', ':open'))
                        ->having($subQueryBuilder->expr()->gte("Count(App.withUser)", ":availability_threshold"))
                        ->groupBy("App.withUser");

        $queryBuilder = $this->userRepo->getQueryBuilder();
        $expr = $queryBuilder->expr();

        $queryBuilder->join("User.roles", "Roles")
                     ->where($expr->notIn("User.userID", $subQueryBuilder->getDQL()))
                     ->andWhere($expr->eq("User.isActive", ":active"))
                     ->andWhere($expr->eq("Roles.roleID", ":role_doc"))
                     ->groupBy("User.userID");

        $queryBuilder->setParameter(":open", Appointment::STATUS_OPEN)
            ->setParameter(":availability_threshold", Appointment::AVAILABILITY_THRESHOLD)
            ->setParameter(":role_doc", Role::ROLE_DOC_ID)
            ->setParameter(":active", User::ACTIVE)
            ->setFirstResult($request->get('from'))
            ->setMaxResults($request->get('step'));

        $pagination = $this->paginator->paginate(
            $queryBuilder,
            $request->get("page", 1),
            $request->get("perPage", self::ITEMS_PER_PAGE)
        );

        $users = $pagination->getItems();

        $usersData = [];

        foreach ($users as $user) {
            $usersData[$user->getUserID()] = [
                "username" => $user->getUsername(),
                "firstName" => $user->getFirstName(),
                "lastName" => $user->getLastName(),
                "roles" => $user->getRoles(),
                "userID" => $user->getUserID(),
                "email" => $user->getEmail()
            ];
        }

        return [
            "users" => $usersData,
            "pagination" => $pagination
        ];
    }

    /**
     * Set the number of the current page
     * @param int $page
     * @return DocsProvider
     */
    public function setCurrentPage($page)
    {
        $this->showPage = (int) $page;
        return $this;
    }
}
