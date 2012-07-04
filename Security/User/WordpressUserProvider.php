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
     * @var ApiAbstraction
     */
    protected $api;

    public function __construct(ApiAbstraction $api)
    {
        $this->api = $api;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        $user = $this->api->get_user_by('login', $username);

        if ($user instanceof \WP_User) {
            return new WordpressUser($user);
        }

        throw new UsernameNotFoundException("User \"{$username}\" could not be found");
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WordpressUser) {
            throw new UnsupportedUserException(
                    sprintf('Instances of "%s" are not supported', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class == 'Hypebeast\\WordpressBundle\\Security\\User\\WordpressUser';
    }

}