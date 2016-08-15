<?php
namespace Docs\AuthBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Docs\AuthBundle\Exception\AuthException;
use Docs\AuthBundle\Security\Authentication\Provider\DocsUserProvider;
use Docs\AuthBundle\Security\Authentication\Token\DocsToken;
use Docs\AuthBundle\Security\Authentication\SecurityUser;

/**
 * This provider will validate the user data
 * @author h.botev
 *
 */
class DocsAuthProvider implements AuthenticationProviderInterface
{
    /**
     * @var \Docs\AuthBundle\Security\Authentication\Provider\DocsUserProvider
     */
    private $userProvider;

    private $cache;

    /**
     * @var $passwordEncoder \Symfony\Component\Security\Core\Encoder\UserPasswordEncoder
     */
    public $passwordEncoder;

    public function __construct(DocsUserProvider $userProvider, $cache, UserPasswordEncoder $passwordEncoder)
    {
        $this->userProvider = $userProvider;
        $this->cache = $cache;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface::authenticate()
     * Log the user in and create and return the authenticated token
     */
    public function authenticate(TokenInterface $token)
    {
        try {
            $username = $token->getAttribute('username');
            $password = $token->getAttribute('password');
            $user = $this->userProvider->getUserByUsernameAndPassword($username);

            $securityUser = new SecurityUser($user);

            $checkPass = $this->passwordEncoder->isPasswordValid($securityUser, $password);

            if ($checkPass) {
                $authenticatedToken = new DocsToken($securityUser->getRoles());
                $authenticatedToken->setAttributes($token->getAttributes());
                $authenticatedToken->setAttribute('userID', $user->getUserID());
                $authenticatedToken->setUser($securityUser);

                return $authenticatedToken;
            }

            return false;
        } catch (AuthException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * Check if the provider supports the current token
     * @param TokenInterface $token
     * @return boolean
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof DocsToken;
    }
}
