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
     * @var integer $umeta_id
     *
     * @ORM\Column(name="umeta_id", type="bigint", length=60)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $umeta_id;

    /**
     * @var integer $user_id
     *
     * @ORM\Column(name="user_id", type="bigint", length=20)
     */
    private $user_id;

    /**
     * @var string $meta_key
     *
     * @ORM\Column(name="meta_key", type="string", length=255)
     */
    private $meta_key;

    /**
     * @var string $meta_value
     *
     * @ORM\Column(name="meta_value", type="text")
     */
    private $meta_value;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->umeta_id;
    }

    /**
     * Set umeta_id
     *
     * @param integer $umetaId
     */
    public function setUmetaId($umetaId)
    {
        $this->umeta_id = $umetaId;
    }

    /**
     * Get umeta_id
     *
     * @return integer 
     */
    public function getUmetaId()
    {
        return $this->umeta_id;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
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
     * @param string $metaValue
     */
    public function setMetaValue($metaValue)
    {
        $this->meta_value = $metaValue;
    }

    /**
     * Get meta_value
     *
     * @return string 
     */
    public function getMetaValue()
    {
        return $this->meta_value;
    }
}