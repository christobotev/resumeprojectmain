<?php
namespace Docs\MainBundle\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Docs\AuthBundle\Security\Authorization\Permission\PermissionManager;
use Docs\AuthBundle\Security\Authentication\Role\Role;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

/**
 * Check if the user has permissions for the current action
 * @author h.botev
 *
 */
class ControllerActionVoter implements VoterInterface
{

    /**
     * @var PermissionManager
     */
    protected $pm;

    public function __construct(PermissionManager $pm)
    {
        $this->pm = $pm;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Authorization\Voter\VoterInterface::supportsAttribute()
     */
    public function supportsAttribute($attribute)
    {
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Authorization\Voter\VoterInterface::supportsClass()
     */
    public function supportsClass($class)
    {
        $supportedClass = 'Symfony\Component\HttpFoundation\Request';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Authorization\Voter\VoterInterface::vote()
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass(get_class($object))
            || !$token->isAuthenticated()
            || $token instanceof AnonymousToken
        ) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $roles = $token->getRoles();

        $roleNames = [];
        foreach ($roles as $role) {
            $roleNames[] = $role->getRole();
        }

        $this->pm->init($roleNames);
        if ($this->pm->isAllowed($this->getResourceName($object))) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }

    /**
     * Return resource name based on controller+action
     * @param Request $request
     * @return string
     */
    protected function getResourceName(Request $request)
    {
        $resourceName = $request->attributes->get("_controller");
        $resourceName = str_replace("\\", "_", $resourceName);
        $resourceName = str_replace("::", "_", $resourceName);

        return $resourceName;
    }
}
