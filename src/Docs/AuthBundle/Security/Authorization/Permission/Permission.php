<?php
namespace Docs\AuthBundle\Security\Authorization\Permission;

/**
 * A permission object
 * @author h.botev
 *
 */
class Permission implements PermissionInterface
{
    protected $id;

    protected $rights;

    public function __construct($id, $rights)
    {
        $this->id = $id;
        $this->rights = $rights;
    }

    /**
     * Return permission id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return permission rights
     * @return int
     */
    public function getAccess()
    {
        return $this->rights;
    }

    /**
     * Return if permission is allowed
     * Currently this should allways return true
     * @return boolean
     */
    public function isAllowed()
    {
        return true;
    }
}
