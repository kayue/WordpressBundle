<?php

/**
 * Contains the WordpressCookieListener class
 *
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @author     Ka Yue Yeung
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\Firewall
 */

namespace Hypebeast\WordpressBundle\Security\Firewall;

use Hypebeast\WordpressBundle\Wordpress\ApiAbstraction;
use Hypebeast\WordpressBundle\Security\Authentication\Token\WordpressCookieToken;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * WordpressCookieListener checks whether the user has been authenticated in Wordpress
 *
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @author     Ka Yue Yeung
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\Firewall
 */
class WordpressCookieListener implements ListenerInterface
{
    /**
     * An abstraction layer through which we can access the Wordpress API
     *
     * @var ApiAbstraction
     */
    protected $api;

    /**
     *
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     *
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     *
     * @var HttpUtils
     */
    protected $httpUtils;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * If true, will redirect to the Wordpress login if the user is not authenticated
     *
     * @var boolean
     */
    protected $redirectToWordpress = false;

    public function __construct(ApiAbstraction $api, SecurityContextInterface $securityContext,
            AuthenticationManagerInterface $authenticationManager, HttpUtils $httpUtils,
            LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null,
            $redirectToWordpress = true)
    {
        $this->api = $api;
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->httpUtils = $httpUtils;
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
        $this->redirectToWordpress = $redirectToWordpress;
    }

    /**
     * Authenticates the Wordpress cookie in the request. Depending upon $redirectToWordpress,
     * either silently returns or redirects to Wordpress login on failure.
     *
     * @param GetResponseEvent $event
     * @return null
     */
    public function handle(GetResponseEvent $event)
    {
        # Don't try to authenticate again if the user already has been
        if ($this->securityContext->getToken()) {
            return;
        }

        try {
            // Authentication manager uses a list of AuthenticationProviderInterface instances
            // to authenticate a Token.
            $token = $this->authenticationManager->authenticate(new WordpressCookieToken);
            $this->securityContext->setToken($token);

            # Notify listeners that the user has been logged in
            if ($this->dispatcher) {
                $this->dispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN,
                        new InteractiveLoginEvent($event->getRequest(), $token));
            }

            if ($this->logger) {
                $this->logger->debug(sprintf(
                        'Wordpress cookie for user "%s" authenticated', $token->getUsername()));
            }

        } catch (AuthenticationException $e) {
            if ($this->redirectToWordpress) {
                # Redirect to Wordpress login, then back to the user's request after they log in
                $request = $event->getRequest();
                $redirectUrl = $this->api->wp_login_url($request->getUri(), true);

                if ($this->logger) {
                    $this->logger->debug(sprintf(
                            'Redirecting to Wordpress login page at %s', $redirectUrl));
                }

                # Clear the security token and redirect
                $this->securityContext->setToken(null);
                $event->setResponse(
                        $this->httpUtils->createRedirectResponse($request, $redirectUrl));
            }
        }
    }
}
