<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\CommentMeta
 *
 * @ORM\Table(name="wp_commentmeta")
 * @ORM\Entity
 */
class CommentMeta
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
     * @var Hypebeast\WordpressBundle\Entity\Comment
     *
     * @ORM\ManyToOne(targetEntity="Hypebeast\WordpressBundle\Entity\Comment", inversedBy="metas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="comment_id", referencedColumnName="comment_ID")
     * })
     */
    private $comment;


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
     * Set comment
     *
     * @param Hypebeast\WordpressBundle\Entity\Comment $comment
     */
    public function setComment(\Hypebeast\WordpressBundle\Entity\Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return Hypebeast\WordpressBundle\Entity\Comment 
     */
    public function getComment()
    {
        return $this->comment;
    }
}