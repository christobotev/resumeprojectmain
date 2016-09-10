<?php
namespace Docs\AuthBundle\Register;

use Docs\CommonBundle\Entity\User;
use Docs\AuthBundle\Security\Authentication\SecurityUser;
use Docs\MainBundle\Persistence\Persister;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Docs\CommonBundle\Entity\Role;
use Docs\CommonBundle\Repository\RoleRepository;

/**
 * class that helps submit
 * register form
 * @author h.botev
 *
 */
class RegisterHelper
{
    /**
     * @var unknown
     */
    protected $passwordEncoder;

    /**
     * @var unknown
     */
    protected $persister;

    /**
     * @var string
     */
    protected $mainPath = 'main';

    /**
     * @var unknown
     */
    protected $rolesRepo;

    /**
     * @param UserPasswordEncoder $passwordEncoder
     * @param Persister $persister
     * @param RoleRepository $rolesRepo
     */
    public function __construct(
        UserPasswordEncoder $passwordEncoder,
        Persister $persister,
        RoleRepository $rolesRepo
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->persister = $persister;
        $this->rolesRepo = $rolesRepo;
    }

    /**
     * @param User $user
     */
    public function submitForm(User $user)
    {
        $securityData = new SecurityUser($user);
        $password = $this->passwordEncoder
                                ->encodePassword($securityData, $user->getPassword());

        $user->setPassword($password);
        $user->setSalt($securityData->generateSalt());
        $user->setGoogleID(0);

        // Add standard ROLE
        $userRoles = $user->getRoles();
        $userRoles[] = $this->rolesRepo
                                ->find(Role::ROLE_USER);

        $this->persister->beginTransaction();
        $this->persister
                    ->persist($user);

        $this->persister->finishTransaction();

        return $this->mainPath;
    }
}
