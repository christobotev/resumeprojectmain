<?php
namespace Docs\AuthBundle\Security\Authentication\Role;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Docs\CommonBundle\Entity\Role as RoleEntity;

class Role implements RoleInterface
{
    protected $roleData;

    public function __construct(RoleEntity $role)
    {
        $this->roleData = [
            'roleID' => $role->getRoleID(),
            'name' => $role->getName()
        ];
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Role\RoleInterface::getRole()
     */
    public function getRole()
    {
        return $this->roleData['name'];
    }

    /**
     * Return role id
     * @return int
     */
    public function getRoleId()
    {
        return $this->roleData['roleID'];
    }
}
