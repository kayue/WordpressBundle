<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\PostMeta
 *
 * @ORM\Table(name="wp_postmeta")
 * @ORM\Entity
 */
class PostMeta
{
    /**
     * @var bigint $id
     *
     * @ORM\Column(name="meta_id", type="bigint", length=20)
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
     * @var Hypebeast\WordpressBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Hypebeast\WordpressBundle\Entity\Post", inversedBy="metas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_id", referencedColumnName="ID")
     * })
     */
    private $post;


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
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return text
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set post
     *
     * @param Hypebeast\WordpressBundle\Entity\Post $post
     */
    public function setPost(\Hypebeast\WordpressBundle\Entity\Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get post
     *
     * @return Hypebeast\WordpressBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
}