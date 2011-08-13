<?php
namespace Hypebeast\WordpressBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="wp_users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="user_login", unique="true", length="60")
     */
    protected $username;

    /**
     * @ORM\Column(name="user_pass", length="64")
     */
    protected $password;

    /**
     * TODO: save user's roles
     */
    protected $roles = array('ROLE_USER'); 
    
    protected $salt;

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    function getUsername() {
        return $this->username;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * @return string The password
     */
    function getPassword() {
        return $this->password;
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return Role[] The user roles
     */
    function getRoles() {
        return $this->roles;
    }

    /**
     * Returns the salt.
     *
     * @return string The salt
     */
    function getSalt() {
        return $this->salt;
    }

    /**
     * Removes sensitive data from the user.
     *
     * @return void
     */
    function eraseCredentials() {
        
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * @param UserInterface $user
     * @return Boolean
     */
    function equals(UserInterface $user) {
        return true;
    }
}