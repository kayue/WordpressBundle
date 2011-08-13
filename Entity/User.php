<?php
namespace Hypebeast\WordpressBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="wp_users")
 *
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="bigint", unique="true", length="20")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
         
    /**
     * @ORM\Column(name="user_login", type="string", unique="true", length="60")
     */
    protected $username;

    /**
     * @ORM\Column(name="user_pass", type="string", length="64")
     */
    protected $password;

    /**
     * @ORM\OneToMany(targetEntity="UserMeta", mappedBy="userId")
     */
    protected $metas;
    
    protected $roles; 
    
    protected $salt;

    public function __construct() {
        $this->metas = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

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
        $return = array();

        foreach($this->metas as $meta) {
            if ($meta->getKey() !== 'wp_capabilities') continue;
            
            $capabilities = unserialize($meta->getValue());

            if(array_key_exists('administrator', $capabilities)) $return[] = 'ROLE_ADMIN';
            if(array_key_exists('subscriber', $capabilities)) $return[] = 'ROLE_USER';

            break;
        }

        return $return;
    }

    function getMetas()
    {
        return $this->metas;
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