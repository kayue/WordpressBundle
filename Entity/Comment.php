<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\Comment
 *
 * @ORM\Table(name="wp_comments")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    /**
     * @var bigint $id
     *
     * @ORM\Column(name="comment_ID", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var text $author
     *
     * @ORM\Column(name="comment_author", type="text")
     */
    private $author;

    /**
     * @var string $authorEmail
     *
     * @ORM\Column(name="comment_author_email", type="string")
     */
    private $authorEmail;

    /**
     * @var string $authorUrl
     *
     * @ORM\Column(name="comment_author_url", type="string")
     */
    private $authorUrl;

    /**
     * @var string $authorIp
     *
     * @ORM\Column(name="comment_author_IP", type="string")
     */
    private $authorIp;

    /**
     * @var datetime $date
     *
     * @ORM\Column(name="comment_date", type="datetime")
     */
    private $date;

    /**
     * @var datetime $dateGmt
     *
     * @ORM\Column(name="comment_date_gmt", type="datetime")
     */
    private $dateGmt;

    /**
     * @var text $content
     *
     * @ORM\Column(name="comment_content", type="text")
     */
    private $content;

    /**
     * @var integer $karma
     *
     * @ORM\Column(name="comment_karma", type="integer")
     */
    private $karma;

    /**
     * @var string $approved
     *
     * @ORM\Column(name="comment_approved", type="string")
     */
    private $approved;

    /**
     * @var string $agent
     *
     * @ORM\Column(name="comment_agent", type="string")
     */
    private $agent;

    /**
     * @var string $type
     *
     * @ORM\Column(name="comment_type", type="string")
     */
    private $type;

    /**
     * @var bigint $parent
     *
     * @ORM\Column(name="comment_parent", type="bigint")
     */
    private $parent;

    /**
     * @var Hypebeast\WordpressBundle\Entity\CommentMeta
     *
     * @ORM\OneToMany(targetEntity="Hypebeast\WordpressBundle\Entity\CommentMeta", mappedBy="comment")
     */
    private $metas;

    /**
     * @var Hypebeast\WordpressBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Hypebeast\WordpressBundle\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="comment_post_ID", referencedColumnName="ID", nullable=false)
     * })
     */
    private $post;

    /**
     * @var Hypebeast\WordpressBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Hypebeast\WordpressBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="ID")
     * })
     */
    private $user;

    public function __construct()
    {
        $this->authorUrl = "";
        $this->authorIp = "";
        $this->karma = 0;
        $this->approved = 1;
        $this->agent = "";
        $this->type = "";
        $this->parent = 0;
        $this->user = null;

        $this->metas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getContent();
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->date    = new \DateTime('now');
        $this->dateGmt = new \DateTime('now', new \DateTimeZone('GMT'));
    }

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
     * Set author
     *
     * @param text $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return text
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set authorEmail
     *
     * @param string $authorEmail
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * Get authorEmail
     *
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * Set authorUrl
     *
     * @param string $authorUrl
     */
    public function setAuthorUrl($authorUrl)
    {
        $this->authorUrl = $authorUrl;
    }

    /**
     * Get authorUrl
     *
     * @return string
     */
    public function getAuthorUrl()
    {
        return $this->authorUrl;
    }

    /**
     * Set authorIp
     *
     * @param string $authorIp
     */
    public function setAuthorIp($authorIp)
    {
        $this->authorIp = $authorIp;
    }

    /**
     * Get authorIp
     *
     * @return string
     */
    public function getAuthorIp()
    {
        return $this->authorIp;
    }

    /**
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date_gmt
     *
     * @param datetime $dateGmt
     */
    public function setDateGmt($dateGmt)
    {
        $this->dateGmt = $dateGmt;
    }

    /**
     * Get date_gmt
     *
     * @return datetime
     */
    public function getDateGmt()
    {
        return $this->dateGmt;
    }

    /**
     * Set content
     *
     * @param text $commentContent
     */
    public function setContent($commentContent)
    {
        $this->content = $commentContent;
    }

    /**
     * Get content
     *
     * @return text
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set karma
     *
     * @param integer $karma
     */
    public function setKarma($karma)
    {
        $this->karma = $karma;
    }

    /**
     * Get karma
     *
     * @return integer
     */
    public function getKarma()
    {
        return $this->karma;
    }

    /**
     * Set approved
     *
     * @param string $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved ? 1 : 0;
    }

    /**
     * Get approved
     *
     * @return string
     */
    public function getApproved()
    {
        return $this->approved === 1;
    }

    /**
     * Set agent
     *
     * @param string $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * Get agent
     *
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set type
     *
     * @param string $commentType
     */
    public function setType($commentType)
    {
        $this->type = $commentType;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set parent
     *
     * @param bigint $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return bigint
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add metas
     *
     * @param Hypebeast\WordpressBundle\Entity\CommentMeta $metas
     */
    public function addCommentMeta(\Hypebeast\WordpressBundle\Entity\CommentMeta $metas)
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

    /**
     * Set user
     *
     * @param Hypebeast\WordpressBundle\Entity\User $user
     */
    public function setUser(\Hypebeast\WordpressBundle\Entity\User $user)
    {
        $this->user = $user;

        $this->author      = $user->getDisplayName();
        $this->authorUrl   = $user->getUrl();
        $this->authorEmail = $user->getEmail();
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
}