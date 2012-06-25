<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\UserMeta
 *
 * @ORM\Table(name="usermeta")
 * @ORM\Entity
 */
class UserMeta
{
    /**
     * @var bigint $id
     *
     * @ORM\Column(name="umeta_id", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $key
     *
     * @ORM\Column(name="meta_key", type="string", length=255, nullable=true)
     */
    private $key;

    /**
     * @var text $value
     *
     * @ORM\Column(name="meta_value", type="text", nullable=true)
     */
    private $value;

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
     * Get id
     *
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->value = serialize($value);
        } else {
            $this->value = $value;
        }
    }

    /**
     * Get value
     *
     * @return text
     */
    public function getValue()
    {
        if($this->isSerialized($this->value)) {
            $this->value = @unserialize($this->value);
        }

        return $this->value;
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
     * @return Hypebeast\WordpressBundle\Entity\User | null
     */
    public function getUser()
    {
        if($this->user instanceof \Doctrine\ORM\Proxy\Proxy) {
            try {
                // prevent lazy loading the user entity becuase it might not exist
                $this->user->__load();
            } catch (\Doctrine\ORM\EntityNotFoundException $e) {
                // return null if user does not exist
                $this->user = null;
            }
        }

        return $this->user;
    }

    /**
     * Check value to find if it was serialized.
     *
     * If $data is not an string, then returned value will always be false.
     * Serialized data is always a string.
     *
     * @param mixed $data Value to check to see if was serialized.
     * @return bool False if not serialized and true if it was.
     */
    private function isSerialized($data) {
        // if it isn't a string, it isn't serialized
        if (!is_string($data))
            return false;
        $data = trim($data);
        if ('N;' == $data)
            return true;
        $length = strlen($data);
        if ($length < 4)
            return false;
        if (':' !== $data[1])
            return false;
        $lastc = $data[$length-1];
        if (';' !== $lastc && '}' !== $lastc)
            return false;
        $token = $data[0];
        switch ($token) {
            case 's' :
                if ( '"' !== $data[$length-2] )
                    return false;
            case 'a' :
            case 'O' :
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b' :
            case 'i' :
            case 'd' :
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;\$/", $data );
        }
        return false;
    }
}