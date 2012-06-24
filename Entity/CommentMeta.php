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
     * @var Hypebeast\WordpressBundle\Entity\Comment
     *
     * @ORM\ManyToOne(targetEntity="Hypebeast\WordpressBundle\Entity\Comment", inversedBy="metas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="comment_id", referencedColumnName="comment_ID")
     * })
     */
    private $comment;

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