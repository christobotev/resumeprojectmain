<?php
namespace Docs\AuthBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Docs\AuthBundle\Security\Authorization\Permission\PermissionManagerInterface;

/**
 * Collector for the permissions of a user
 * @author h.botev
 *
 */
class AclDataCollector extends DataCollector
{
    /**
     * @var PermissionManagerInterface
     */
    protected $pm;

    /**
     * Set the permission manager
     * @param PermissionManagerInterface $pm
     */
    public function __construct(PermissionManagerInterface $pm)
    {
        $this->pm = $pm;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface::getName()
     */
    public function getName()
    {
        return "acl_permissions";
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface::collect()
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['acl_permissions'] = $this->pm->getPermissions();
    }

    /**
     * Return the collector data
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
