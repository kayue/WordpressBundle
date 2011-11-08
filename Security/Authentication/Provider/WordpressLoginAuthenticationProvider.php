<?php

/**
 * Contains the WordpressLoginAuthenticationProvider class, part of the Symfony2 Wordpress Bundle
 *
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\Authentication\Provider
 */

namespace Hypebeast\WordpressBundle\Security\Authentication\Provider;

use Hypebeast\WordpressBundle\Wordpress\ApiAbstraction;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Hypebeast\WordpressBundle\Security\User\WordpressUser;

/**
 * WordpressLoginAuthenticationProvider will authenticate the user with Wordpress
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\Authentication\Provider
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 */
class WordpressLoginAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * An abstraction layer for the Wordpress API
     *
     * @var ApiAbstraction
     */
    protected $api;
    
    /**
     *
     * @var string
     */
    protected $rememberMeParameter;
    
    /**
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ApiAbstraction $api 
     * @param string $rememberMeParameter the name of the request parameter to use to determine 
     *                                    whether to remember the user
     * @param ContainerInterface $container so we can get the request and check the remember-me param
     */
    public function __construct(ApiAbstraction $api, $rememberMeParameter = '_remember_me',
            ContainerInterface $container = null)
    {
        $this->api = $api;
        $this->rememberMeParameter = $rememberMeParameter;
        $this->container = $container;
    }

    public function authenticate(TokenInterface $token)
    {
        # If the user is already logged-in, just check that their credentials are still valid
        if ($token->getUser() instanceof UserInterface) {
            $wpUser = $this->api->get_user_by('login', $token->getUsername());
            
        } else {
            $wpUser = $this->api->wp_signon(array(
                    'user_login' => $token->getUsername(),
                    'user_password' => $token->getCredentials(),
                    'remember' => $this->isRememberMeRequested()
            ));
        }
        
        if ($wpUser instanceof \WP_User) {
            $user = new WordpressUser($wpUser);
            $authenticatedToken = new UsernamePasswordToken(
                    $user, $token->getCredentials(), $token->getProviderKey(), $user->getRoles());
            
            return $authenticatedToken;
            
        } else if ($wpUser instanceof \WP_Error) {
            throw new AuthenticationException(implode(', ', $wpUser->get_error_messages()));
        }

        throw new AuthenticationServiceException('The Wordpress API returned an invalid response');
    }
    
    /**
     * Checks whether the user requested to be remembered
     *
     * @return boolean
     */
    protected function isRememberMeRequested()
    {
        if (!($this->container && $request = $this->container->get('request'))) {
            return false;
        }

        $remember = $request->request->get($this->rememberMeParameter, null, true);

        return $remember === 'true' || $remember === 'on' || $remember === '1' || $remember === 'yes';
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof UsernamePasswordToken;
    }
}