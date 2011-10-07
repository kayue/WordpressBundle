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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;

/**
 * WordpressCookieListener initiates authentication of the user against Wordpress and redirects to its 
 * login mechanism if the user is not already authenticated
 *
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @author     Ka Yue Yeung
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\Firewall
 */
class WordpressCookieListener extends AbstractAuthenticationListener
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
     * If true, will redirect to the Wordpress login if the user is not authenticated
     *
     * @var boolean
     */
    protected $redirectToWordpress = false;

    public function __construct(ApiAbstraction $api, SecurityContextInterface $securityContext,
            AuthenticationManagerInterface $authenticationManager, HttpUtils $httpUtils,
            LoggerInterface $logger=null, $redirectToWordpress=true)
    {
        parent::__construct($securityContext, $authenticationManager,
                new SessionAuthenticationStrategy(SessionAuthenticationStrategy::NONE), $httpUtils,
                'wordpress'
        );
        
        $this->api = $api;
        $this->securityContext = $securityContext;
        $this->logger = $logger;
        $this->redirectToWordpress = $redirectToWordpress;
    }

    protected function attemptAuthentication(Request $request) 
    {
        # Don't try to authenticate again if the user already has been
        if ($this->securityContext->getToken()) {
            return;
        }
        
        try {
            // Authentication manager uses a list of AuthenticationProviderInterface instances 
            // to authenticate a Token.
            return $this->authenticationManager->authenticate(new WordpressCookieToken);
            
        } catch (AuthenticationException $e) {
            if ($this->redirectToWordpress) {
                # Redirect to Wordpress login, then back to the user's request after they log in
                $redirect_url = $this->api->wp_login_url($request->getUri(), true);
                
                if (null !== $this->logger) {
                    $this->logger->debug(sprintf('Redirecting to Wordpress login page at %s',
                            $redirect_url));
                }

                $this->securityContext->setToken(null);
                return $this->httpUtils->createRedirectResponse($request, $redirect_url);
            }
        }
    }
}
