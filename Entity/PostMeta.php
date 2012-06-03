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
     * @var bigint $meta_id
     *
     * @ORM\Column(name="meta_id", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $meta_id;

    /**
     * @var string $meta_key
     *
     * @ORM\Column(name="meta_key", type="string", length=255, nullable=true)
     */
    private $meta_key;

    /**
     * @var text $meta_value
     *
     * @ORM\Column(name="meta_value", type="text", nullable=true)
     */
    private $meta_value;

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
     * Get meta_id
     *
     * @return bigint 
     */
    public function getMetaId()
    {
        return $this->meta_id;
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