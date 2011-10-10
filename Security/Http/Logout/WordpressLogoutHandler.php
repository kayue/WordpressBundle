<?php

/**
 * Contains the WordpressLogoutHandler class, part of the Symfony2 Wordpress bundle
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\Http\Logout
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 */

namespace Hypebeast\WordpressBundle\Security\Http\Logout;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Hypebeast\WordpressBundle\Wordpress\ApiAbstraction;

/**
 * Handles logging out of Wordpress when the user logs out of Symfony
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\Http\Logout
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 */
class WordpressLogoutHandler implements LogoutHandlerInterface
{
    
    /**
     *
     * @var ApiAbstraction
     */
    protected $api;

    /**
     * Constructor
     *
     * @param ApiAbstraction $api 
     */
    public function __construct(ApiAbstraction $api)
    {
        $this->api = $api;
    }
    
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $this->api->wp_logout();
    }

}