<?php

/**
 * Contains the WordpressUserProvider class, part of the Symfony WordPress Bundle
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\User
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 */

namespace Hypebeast\WordpressBundle\Security\User;

use Hypebeast\WordpressBundle\Wordpress\ApiAbstraction;
use Hypebeast\WordpressBundle\Security\User\WordpressUser;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * A user provider for WordPress users
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\User
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 */
class WordpressUserProvider implements UserProviderInterface
{
    
    /**
     *
     * @var ApiAbstraction
     */
    protected $api;

    public function __construct(ApiAbstraction $api)
    {
        $this->api = $api;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->api->get_user_by('login', $username);
        if ($user instanceof \WP_User) {
            return new WordpressUser($user);
        }
        
        throw new UsernameNotFoundException("User \"{$username}\" could not be found");
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WordpressUser) {
            throw new UnsupportedUserException(
                    sprintf('Instances of "%s" are not supported', get_class($user)));
        }
        
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class == 'Hypebeast\\WordpressBundle\\Security\\User\\WordpressUser';
    }

}