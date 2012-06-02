<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\UserMeta
 *
 * @ORM\Table(name="wp_usermeta")
 * @ORM\Entity
 */
class UserMeta
{
    /**
     * @var bigint $umeta_id
     *
     * @ORM\Column(name="umeta_id", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $umeta_id;

    /**
     * @var string $meta_key
     *
     * @ORM\Column(name="meta_key", type="string", length=255)
     */
    private $meta_key;

    /**
     * @var text $meta_value
     *
     * @ORM\Column(name="meta_value", type="text")
     */
    private $meta_value;

    /**
     * @var Hypebeast\WordpressBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Hypebeast\WordpressBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="ID")
     * })
     */
    private $user;


    /**
     * Get umeta_id
     *
     * @return bigint 
     */
    public function getUmetaId()
    {
        return $this->umeta_id;
    }

    /**
     * Set meta_key
     *
     * @param string $metaKey
     */
    public function setMetaKey($metaKey)
    {
        $this->meta_key = $metaKey;
    }

    /**
     * Get meta_key
     *
     * @return string 
     */
    public function getMetaKey()
    {
        return $this->meta_key;
    }

    /**
     * Set meta_value
     *
     * @param text $metaValue
     */
    public function setMetaValue($metaValue)
    {
        $this->meta_value = $metaValue;
    }

    /**
     * Get meta_value
     *
     * @return text 
     */
    public function getMetaValue()
    {
        return $this->meta_value;
    }

    /**
     * Set user
     *
     * @param Hypebeast\WordpressBundle\Entity\User $user
     */
    public function setUser(\Hypebeast\WordpressBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Hypebeast\WordpressBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}