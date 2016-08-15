<?php
namespace Docs\MainBundle\Security\Voter;

use Docs\AuthBundle\Security\Authorization\Permission\PermissionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Check for system permissions
 * @author h.botev
 *
 */
class SystemPermissionsVoter implements VoterInterface
{

    /**
     * @var \Docs\AuthBundle\Security\Authorization\Permission\PermissionManagerInterface
     */
    protected $permissionManager;

    /**
     * @param PermissionManagerInterface $permissionManager
     */
    public function __construct(PermissionManagerInterface $permissionManager)
    {
        $this->permissionManager = $permissionManager;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Authorization\Voter\VoterInterface::vote()
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $vote = static::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $vote = static::ACCESS_DENIED;

            if ($this->permissionManager->isAllowed($attribute)) {
                return static::ACCESS_GRANTED;
            }
        }

        return $vote;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Authorization\Voter\VoterInterface::supportsAttribute()
     */
    public function supportsAttribute($attribute)
    {
        return strpos($attribute, "system.") === 0;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Authorization\Voter\VoterInterface::supportsClass()
     */
    public function supportsClass($class)
    {
        return false;
    }
}
