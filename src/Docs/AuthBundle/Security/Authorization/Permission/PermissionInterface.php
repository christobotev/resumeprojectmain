<?php
namespace Docs\AuthBundle\Security\Authorization\Permission;

/**
 * Interface implemented by the permission objects
 * @author h.botev
 *
 */
interface PermissionInterface
{
    /**
     * Return permission id
     * @return int
     */
    public function getId();

    /**
     * Return permission rights
     * @return int
     */
    public function getAccess();

    /**
     * Check if permission in allowed
     * @return bool
     */
    public function isAllowed();
}
