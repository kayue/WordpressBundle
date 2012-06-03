<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\User
 *
 * @ORM\Table(name="wp_users")
 * @ORM\Entity
 */
class User
{
    /**
     * @var bigint $ID
     *
     * @ORM\Column(name="ID", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ID;

    /**
     * @var string $user_login
     *
     * @ORM\Column(name="user_login", type="string", length=60, unique=true)
     */
    private $user_login;

    /**
     * @var string $user_pass
     *
     * @ORM\Column(name="user_pass", type="string", length=64)
     */
    private $user_pass;

    /**
     * @var string $user_nicename
     *
     * @ORM\Column(name="user_nicename", type="string", length=64)
     */
    private $user_nicename;

    /**
     * @var string $user_email
     *
     * @ORM\Column(name="user_email", type="string", length=100)
     */
    private $user_email;

    /**
     * @var string $user_url
     *
     * @ORM\Column(name="user_url", type="string", length=100)
     */
    private $user_url;

    /**
     * @var datetime $user_registered
     *
     * @ORM\Column(name="user_registered", type="datetime")
     */
    private $user_registered;

    /**
     * @var string $user_activation_key
     *
     * @ORM\Column(name="user_activation_key", type="string", length=60)
     */
    private $user_activation_key;

    /**
     * @var integer $user_status
     *
     * @ORM\Column(name="user_status", type="integer", length=11)
     */
    private $user_status;

    /**
     * @var string $display_name
     *
     * @ORM\Column(name="display_name", type="string", length=250)
     */
    private $display_name;

    /**
     * @var Hypebeast\WordpressBundle\Entity\Post
     *
     * @ORM\OneToMany(targetEntity="Hypebeast\WordpressBundle\Entity\Post", mappedBy="user")
     */
    private $posts;

    /**
     * @var Hypebeast\WordpressBundle\Entity\Comment
     *
     * @ORM\OneToMany(targetEntity="Hypebeast\WordpressBundle\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @var Hypebeast\WordpressBundle\Entity\UserMeta
     *
     * @ORM\OneToMany(targetEntity="Hypebeast\WordpressBundle\Entity\UserMeta", mappedBy="user")
     */
    private $metas;

    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    $this->metas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get ID
     *
     * @return bigint 
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * Set user_login
     *
     * @param string $userLogin
     */
    public function setUserLogin($userLogin)
    {
        $this->user_login = $userLogin;
    }

    /**
     * Get user_login
     *
     * @return string 
     */
    public function getUserLogin()
    {
        return $this->user_login;
    }

    /**
     * Set user_pass
     *
     * @param string $userPass
     */
    public function setUserPass($userPass)
    {
        $this->user_pass = $userPass;
    }

    /**
     * Get user_pass
     *
     * @return string 
     */
    public function getUserPass()
    {
        return $this->user_pass;
    }

    /**
     * Set user_nicename
     *
     * @param string $userNicename
     */
    public function setUserNicename($userNicename)
    {
        $this->user_nicename = $userNicename;
    }

    /**
     * Get user_nicename
     *
     * @return string 
     */
    public function getUserNicename()
    {
        return $this->user_nicename;
    }

    /**
     * Set user_email
     *
     * @param string $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->user_email = $userEmail;
    }

    /**
     * Get user_email
     *
     * @return string 
     */
    public function getUserEmail()
    {
        return $this->user_email;
    }

    /**
     * Set user_url
     *
     * @param string $userUrl
     */
    public function setUserUrl($userUrl)
    {
        $this->user_url = $userUrl;
    }

    /**
     * Get user_url
     *
     * @return string 
     */
    public function getUserUrl()
    {
        return $this->user_url;
    }

    /**
     * Set user_registered
     *
     * @param datetime $userRegistered
     */
    public function setUserRegistered($userRegistered)
    {
        $this->user_registered = $userRegistered;
    }

    /**
     * Get user_registered
     *
     * @return datetime 
     */
    public function getUserRegistered()
    {
        return $this->user_registered;
    }

    /**
     * Set user_activation_key
     *
     * @param string $userActivationKey
     */
    public function setUserActivationKey($userActivationKey)
    {
        $this->user_activation_key = $userActivationKey;
    }

    /**
     * Get user_activation_key
     *
     * @return string 
     */
    public function getUserActivationKey()
    {
        return $this->user_activation_key;
    }

    /**
     * Set user_status
     *
     * @param integer $userStatus
     */
    public function setUserStatus($userStatus)
    {
        $this->user_status = $userStatus;
    }

    /**
     * Get user_status
     *
     * @return integer 
     */
    public function getUserStatus()
    {
        return $this->user_status;
    }

    /**
     * Set display_name
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->display_name = $displayName;
    }

    /**
     * Get display_name
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * Add posts
     *
     * @param Hypebeast\WordpressBundle\Entity\Post $posts
     */
    public function addPost(\Hypebeast\WordpressBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Get posts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add comments
     *
     * @param Hypebeast\WordpressBundle\Entity\Comment $comments
     */
    public function addComment(\Hypebeast\WordpressBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add metas
     *
     * @param Hypebeast\WordpressBundle\Entity\UserMeta $metas
     */
    public function addUserMeta(\Hypebeast\WordpressBundle\Entity\UserMeta $metas)
    {
        $this->metas[] = $metas;
    }

    /**
     * Get metas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMetas()
    {
        return $this->metas;
    }
}