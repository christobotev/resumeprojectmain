<?php
namespace Docs\AuthBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Docs\CommonBundle\Entity\User;
use Docs\CommonBundle\Entity\Role;
use Docs\AuthBundle\Security\Authentication\SecurityUser;
use Docs\AuthBundle\Exception\AuthException;

/**
 * @author h.botev
 */
class DocsUserProvider extends OAuthUserProvider implements UserProviderInterface
{
    /**
     * @var EntityManager
     */
    public $entityManager;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param string|integer $casID
     * @return Docs\CommonBundle\Entity\User object
     * @throws AuthException
     */
    public function getUserIdById($userID)
    {
        $userRepo = $this->entityManager->getRepository('Docs\CommonBundle\Entity\User');
        $results = $userRepo->findOneBy(['userID'=> $userID]);

        if (!$results) {
            throw new AuthException('User does not exist.');
        }

        return $results;
    }

    public function getUserByUsernameAndPassword($username)
    {
        $userRepo = $this->entityManager->getRepository('Docs\CommonBundle\Entity\User');
        $results = $userRepo->findOneBy(['username'=> $username]);

        if (!$results) {
            throw new AuthException('User can\'t be found.');
        }

        return $results;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $google_id = $response->getUsername();
        $username = $response->getNickname();
        $email = $response->getEmail();

        //Check if this Google user already exists in our app DB
        $userRepo = $this->entityManager->getRepository("\Docs\CommonBundle\Entity\User");

        $qb = $userRepo->createQueryBuilder('User');
        $qb->select('User')
                ->where('User.googleID = :gid')
                ->setParameter('gid', $google_id)
                ->setMaxResults(1);

        $result = $qb->getQuery()
                            ->getResult();

        //add to database if doesn't exists
        if (!count($result)) {
            $role = $this->getGoogleRole();

            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setGoogleID($google_id);
            $user->setRoles([$role]);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new SecurityUser($user);
        }

        return new SecurityUser($result[0]);
    }

    /**
     * {@inheritDoc}
     * @see \HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider::loadUserByUsername()
     */
    public function loadUserByUsername($username)
    {
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserProviderInterface::refreshUser()
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return $class === 'Docs\\CommonBundle\\Entity\\User';
    }

    /**
     * @return \Docs\CommonBundle\Entity\Role
     */
    protected function getGoogleRole()
    {
        $rolesRepo = $this->entityManager->getRepository("\Docs\CommonBundle\Entity\Role");
        $role = $rolesRepo->findOneBy(['name' => Role::ROLE_GOOGLE_USER]);

        return $role;
    }
}
