<?php
namespace Hypebeast\WordpressBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity(repositoryClass="Hypebeast\WordpressBundle\Repository\UserRepository")
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
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set roles
     *
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return Role[] The user roles
     */
    function getRoles() {
        foreach($this->metas as $meta) {
            if ($meta->getKey() !== 'wp_capabilities') continue;
            
            $capabilities = unserialize($meta->getValue());

            if(array_key_exists('administrator', $capabilities)) $this->roles[] = 'ROLE_ADMIN';
            if(array_key_exists('subscriber', $capabilities)) $this->roles[] = 'ROLE_USER';

            break;
        }

        return $this->roles;
    }

    /**
     * Add metas
     *
     * @param Hypebeast\WordpressBundle\Entity\UserMeta $metas
     */
    public function addMetas(\Hypebeast\WordpressBundle\Entity\UserMeta $metas)
    {
        $this->metas[] = $metas;
    }

    /**
     * Get metas
     *
     * @return metas[]
     */
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
        return ($this->getId() === $user->getId()) && ($this->getUsername() === $user->getUsername());
    }
}