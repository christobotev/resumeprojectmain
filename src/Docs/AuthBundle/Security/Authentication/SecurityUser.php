<?php
namespace Docs\AuthBundle\Security\Authentication;

use Docs\CommonBundle\Entity\User as UserEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Docs\CommonBundle\Entity\Role;

/**
 *
 * @author h.botev
 */
class SecurityUser implements UserInterface
{
    /**
     * @var \Docs\CommonBundle\Entity\User
     */
    protected $user;

    /**
     * @param UserEntity $user
     */
    public function __construct(UserEntity $user)
    {
        $this->user = $user;
    }

    /**
     * Return the username of the user
     */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * Return the user entity
     * @return \Docs\CommonBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Return the username of the user
     * @return string
     */
    public function getUsername()
    {
        if ($this->user instanceof UserEntity) {
            return $this->user->getUsername();
        }
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getRoles()
     */
    public function getRoles()
    {
        if ($this->user instanceof UserEntity) {
            $roles = $this->user->getRoles();
            foreach ($roles as $role) {
                $arrayNames[] = $role->getName();
            }
            return $arrayNames;
        }
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getPassword()
     */
    public function getPassword()
    {
        if ($this->user instanceof UserEntity) {
            return $this->user->getPassword();
        }
    }

    /**
     * Get security user ID
     */
    public function getUserID()
    {
        if ($this->user instanceof UserEntity) {
            return $this->user->getUserID();
        }
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getSalt()
     */
    public function getSalt()
    {
        if ($this->user instanceof UserEntity
            && $this->user->hasSalt()) {
            return $this->user->getSalt();
        }
    }

    /**
     * Generate Salt
     * @return string
     */
    public function generateSalt()
    {
        $randomString = md5($this->generateRandomString());
        if ($this->user instanceof UserEntity) {
            $this->user->setSalt($randomString);
        }

        return $randomString;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
    }

    /**
     * Generates random string
     * @param number $length
     */
    protected function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * Serialize, unserialize:
     * Used when reading the securityToken
     * from the session
     */
    public function serialize ()
    {
        return serialize(
            [$this->user->getUserID(),
            $this->user->getUsername(),
            $this->user->getPassword(),
            $this->user->getSalt()]
        );
    }

    /**
     * @param $serialized
     */
    public function unserialize ($serialized)
    {
    }

    /**
     * Check wether the user
     * is a doctor or not
     * @return boolean
     */
    public function isDoctor()
    {
        if ($this->user instanceof UserEntity) {
            $roles = $this->user->getRoles();
            foreach ($roles as $role) {
                if ($role->getRoleID() == Role::ROLE_DOC_ID) {
                    return true;
                }
            }
            return false;
        }
    }
}
