<?php
namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="wp_usermeta")
 *
 */
class UserMeta
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="umeta_id", type="bigint", length=60)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $userId
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="metas")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="ID")
     */
    private $userId;

    /**
     * @var string $key
     *
     * @ORM\Column(name="meta_key", type="string", length=255)
     */
    private $key;

    /**
     * @var string $value
     *
     * @ORM\Column(name="meta_value", type="text")
     */
    private $value;

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
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set meta_key
     *
     * @param string $metaKey
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get meta_key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set meta_value
     *
     * @param string $metaValue
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get meta_value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}