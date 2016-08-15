<?php
namespace Docs\AuthBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Docs\AuthBundle\Security\Authentication\Role\Role;

class DocsToken extends AbstractToken
{
    public function __construct(array $roles = [])
    {
        parent::__construct($roles);

        // set session ID
        if (!empty($sessionID)) {
             $this->setAttribute('sessionID', $sessionID);
        }

        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {

    }

    public function getUsername()
    {
        if ($this->hasAttribute('username')) {
            return $this->getAttribute('username');
        }
    }
}
