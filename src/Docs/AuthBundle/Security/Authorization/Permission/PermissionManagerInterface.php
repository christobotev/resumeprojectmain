<?php
namespace Docs\AuthBundle\Security\Authorization\Permission;

/**
 * Permission manager interface
 * @author h.botev
 *
 */
interface PermissionManagerInterface
{
    /**
     * Return permission
     * @param string $id
     * @return \Docs\AuthBundle\Security\Authorization\Permission\PermissionInterface
     */
    public function getPermission($id);

    /**
     * Check if permission is allowed
     * @param string $id
     * @return bool
     */
    public function isAllowed($id);

    /**
     * Return a list of permissions
     * @return array
     */
    public function getPermissions();
}
