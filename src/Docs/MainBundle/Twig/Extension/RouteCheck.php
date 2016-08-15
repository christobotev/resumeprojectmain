<?php
namespace Docs\MainBundle\Twig\Extension;

use Docs\AuthBundle\Security\Authorization\Permission\PermissionManager;
use Symfony\Component\Routing\RouterInterface;

/**
 * Check if the user has permissions for a route
 * @author h.botev
 *
 */
class RouteCheck extends \Twig_Extension
{

    /**
     * @var \Docs\AuthBundle\Security\Authorization\Permission\PermissionManager
     */
    protected $permissionManager;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @param PermissionManager $permissionManager
     * @param RouterInterface $router
     */
    public function __construct(PermissionManager $permissionManager, RouterInterface $router)
    {
        $this->permissionManager = $permissionManager;
        $this->router = $router;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("hasPermissions", [$this, "hasPermissions"])
        ];
    }

    /**
     * Check if the user has permissions to a route
     * @param string $route
     * @return boolean
     */
    public function hasPermissions($route)
    {
        $routeConfig = $this->router->getRouteCollection()->get($route);

        $controller = $routeConfig->getDefault("_controller");

        $resourceName = str_replace(["\\", "::"], "_", $controller);

        return $this->permissionManager->isAllowed($resourceName);
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return "routeChecker";
    }
}
