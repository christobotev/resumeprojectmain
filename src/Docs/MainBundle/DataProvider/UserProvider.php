<?php
namespace Docs\MainBundle\DataProvider;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface as Paginator;
use Symfony\Component\HttpFoundation\Request;

class UsersProvider implements DataProviderInterface
{
    /**
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    protected $paginator;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager, Paginator $paginator)
    {
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
    }

    public function getUsers(Request $request)
    {
        $usersRepo = $this->entityManager->getRepository("Docs\CommonBundle\Entity\User");
        /* @var $usersRepo \Docs\CommonBundle\Repository\UserRepository */

        $queryBuilder = clone $usersRepo->getQueryBuilder();

        $pagination = $this->paginator->paginate(
            $queryBuilder,
            $request->get("page", 1),
            $request->get("perPage", 10)
        );

        $users = $pagination->getItems();

        $usersData = [];

        foreach ($users as $user) {
            $usersData[$user->getCasID()] = [
                "userID" => $user->getUserID(),
                "roles" => $user->getRoles()
            ];
        }

        $usersData = $this->decoratorManager->decorate("employeesCasData", $usersData);

        return [
            "users" => $usersData,
            "pagination" => $pagination
        ];
    }
}
