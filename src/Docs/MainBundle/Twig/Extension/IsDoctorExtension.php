<?php
namespace Docs\MainBundle\Twig\Extension;

use Docs\CommonBundle\Entity\Role;

/**
 * Check whether the user has ROLE_DOC
 * @author h.botev
 *
 */
class IsDoctorExtension extends \Twig_Extension
{
    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("isDoc", [$this, "isDoctor"])
        ];
    }

    /**
     * Check if user is doctor
     * @param string $route
     * @return boolean
     */
    public function isDoctor($roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if ($role === Role::ROLE_DOC) {
                return true;
            }
        }

        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return "isDoc";
    }
}
