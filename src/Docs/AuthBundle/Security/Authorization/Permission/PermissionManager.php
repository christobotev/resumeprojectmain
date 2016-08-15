<?php
namespace Docs\AuthBundle\Security\Authorization\Permission;

use Doctrine\ORM\EntityManager;

/**
 * Manage role parmissions
 * @author h.botev
 *
 */
class PermissionManager implements PermissionManagerInterface
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var PermissionFileWriter
     */
    protected $writer;

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @var bool
     */
    protected $fromCache;

    /**
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * @param EntityManager $entityManager
     * @param string $cacheDir
     * @param boolean $fromCache
     */
    public function __construct(EntityManager $entityManager, $cacheDir, $fromCache = true)
    {
        $this->entityManager = $entityManager;
        $this->writer = new PermissionFileWriter($cacheDir);
        $this->fromCache = $fromCache;
    }

    /**
     * Init the permission manager
     * @param unknown $roleID
     */
    public function init($roleName)
    {
        if ($this->isInitialized) {
            return;
        }

        if ($this->fromCache) {
            $this->loadFromCache($roleName);
        } else {
            $this->load($roleName);
        }

        $this->isInitialized = true;
    }

    /**
     * Return permission object
     * @param string $id
     * @return \Docs\AuthBundle\Security\Authorization\Permission\PermissionInterface
     */
    public function getPermission($id)
    {

        if (!isset($this->permissions[$id])) {
            return false;
        }

        return $this->permissions[$id];
    }

    /**
     * Check if permission is allowed
     * @param string $id
     * @return bool
     */
    public function isAllowed($id)
    {
        if (!isset($this->permissions[$id])) {
            return false;
        }

        return $this->permissions[$id]->isAllowed();

    }

    /**
     * Return a list of permissions
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Load permissions from cache
     * @param int $roleID
     */
    protected function loadFromCache($roleName)
    {
        $permissions = require $this->writer->getCacheFileName($roleName);

        if (empty($permissions)) {
            return $this->load($roleName, true);
        } else {
            $this->permissions = $permissions;
        }

        return $this;
    }

    /**
     * Load permissions
     * @param int $roleID
     * @param string $cache
     */
    protected function load($roleName, $cache = false)
    {
        $resourceRepo = $this->entityManager
                                ->getRepository("Docs\CommonBundle\Entity\RoleResources");
        /* @var $resourceRepo \Docs\CommonBundle\Repository\RoleResourcesRepository */

        $resourcesQB = $resourceRepo->createQueryBuilder("RoleResources");

        $resourcesQB->innerJoin("RoleResources.role", "Role")
                    ->where($resourcesQB->expr()->eq("Role.name", ":roleName"))
                    ->setParameter(":roleName", $roleName);

        $resourcesData = $resourcesQB->getQuery()->getResult();
        foreach ($resourcesData as $data) {
            $this->permissions[$data->getResource()->getName()] = new Permission(
                $data->getResource()->getName(),
                $data->getRights()
            );
        }

        if ($cache) {
            $this->dumpPermissions($this->permissions, $roleName);
        }

        return $this;
    }

    /**
     * Dump permissions in a cache
     * @param array $permissions
     * @param int $roleID
     */
    protected function dumpPermissions(array $permissions, $roleName)
    {

        $this->writer->write($permissions, $roleName);

        return $this;
    }
}
