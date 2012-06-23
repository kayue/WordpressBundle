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
     * @var bigint $id
     *
     * @ORM\Column(name="ID", type="wordpressid", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $username
     *
     * @ORM\Column(name="user_login", type="string", length=60, unique=true)
     */
    private $username;

    /**
     * @var string $password
     *
     * @ORM\Column(name="user_pass", type="string", length=64)
     */
    private $password;

    /**
     * @var string $nicename
     *
     * @ORM\Column(name="user_nicename", type="string", length=64)
     */
    private $nicename;

    /**
     * @var string $email
     *
     * @ORM\Column(name="user_email", type="string", length=100)
     */
    private $email;

    /**
     * @var string $url
     *
     * @ORM\Column(name="user_url", type="string", length=100)
     */
    private $url;

    /**
     * @var datetime $registeredDate
     *
     * @ORM\Column(name="user_registered", type="datetime")
     */
    private $registeredDate;

    /**
     * @var string $activationKey
     *
     * @ORM\Column(name="user_activation_key", type="string", length=60)
     */
    private $activationKey;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="user_status", type="integer", length=11)
     */
    private $status;

    /**
     * @var string $displayName
     *
     * @ORM\Column(name="display_name", type="string", length=250)
     */
    private $displayName;

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
        $this->posts    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->metas    = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get ID
     *
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $userLogin
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
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
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set nicename
     *
     * @param string $nicename
     */
    public function setNicename($nicename)
    {
        $this->nicename = $nicename;
    }

    /**
     * Get nicename
     *
     * @return string
     */
    public function getNicename()
    {
        return $this->nicename;
    }

    /**
     * Set email
     *
     * @param string $userEmail
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set registeredDate
     *
     * @param datetime $userregisteredDate
     */
    public function setRegisteredDate($registeredDate)
    {
        $this->registeredDate = $registeredDate;
    }

    /**
     * Get registeredDate
     *
     * @return datetime
     */
    public function getRegisteredDate()
    {
        return $this->registeredDate;
    }

    /**
     * Set activationKey
     *
     * @param string $activationKey
     */
    public function setActivationKey($activationKey)
    {
        $this->activationKey = $activationKey;
    }

    /**
     * Get activationKey
     *
     * @return string
     */
    public function getActivationKey()
    {
        return $this->activationKey;
    }

    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
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
     * Add meta
     *
     * @param Hypebeast\WordpressBundle\Entity\UserMeta $meta
     */
    public function addMeta(\Hypebeast\WordpressBundle\Entity\UserMeta $meta)
    {
        $this->metas[] = $meta;
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

    /**
     * Get metas by meta key
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getMetasByKey($key)
    {
        return $this->getMetas()->filter(function($meta) use ($key) {
            return $meta->getKey() === $key;
        });
    }
}