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

    public function __construct(ApiAbstraction $api, SecurityContextInterface $securityContext,
            AuthenticationManagerInterface $authenticationManager, HttpUtils $httpUtils,
            LoggerInterface $logger=null)
    {
        parent::__construct($securityContext, $authenticationManager,
                new SessionAuthenticationStrategy(SessionAuthenticationStrategy::NONE), $httpUtils,
                'wordpress'
        );
        
        $this->api = $api;
        $this->logger = $logger;
    }

    protected function attemptAuthentication(Request $request) 
    {
        # Redirect to the Wordpress login and then back to the user's requst after they log in
        $this->options['failure_path'] = $this->api->wp_login_url($request->getUri(), true);
        
        // Authentication manager uses a list of AuthenticationProviderInterface instances 
        // to authenticate a Token.
        return $this->authenticationManager->authenticate(new WordpressCookieToken);
    }
}
