<?php
namespace Docs\AuthBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Docs\AuthBundle\Security\Authentication\Token\DocsToken;
use Monolog\Logger;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * AbstractAuthenticationListener has 'handle' as final
 * and we don't want that, but we still want the checkPath
 * and requiresAuthentication check (our own way)
 * @author hbotev
 *
 */
class AuthListener implements ListenerInterface
{
    protected $tokenStorage;
    protected $authenticationManager;
    protected $logger;
    protected $httpUtils;
    protected $checkPath;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        Logger $logger,
        HttpUtils $httpUtils
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->logger = $logger;
        $this->httpUtils = $httpUtils;
    }

    public function setCheckPaths($checkPath)
    {
        $this->checkPath = $checkPath;
    }

    /**
     * {@inheritDoc}
     */
    public function requiresAuthentication(Request $request)
    {
        // Check if the route needs auth
        if ($this->httpUtils->checkRequestPath($request, $this->checkPath)) {
            return true;
        }

        return false;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$this->requiresAuthentication($request)) {
            return;
        }

        $unAuthToken = new DocsToken([]);

        $loginCred = $request->get('login');
        $unAuthToken->setAttributes(
            ['username' => $loginCred['username'],
            'password' => $loginCred['password']]
        );

        try {
            $authToken = $this->authenticationManager->authenticate($unAuthToken);
            $this->tokenStorage->setToken($authToken);

            if (null !== $this->logger) {
                $this->logger->info('Populated the TokenStorage with a DocsToken.');
            }

            return;
        } catch (AuthenticationException $failed) {
            $token = $this->tokenStorage->getToken();
            if ($token instanceof DocsToken) {
                $this->tokenStorage->setToken(null);
            }

            $response = new Response("You are not allowed to log in the system");
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            $event->setResponse($response);
            return;
        }

        $response = new Response("You are not allowed to log in the system");
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
