<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\Comment
 *
 * @ORM\Table(name="wp_comments")
 * @ORM\Entity
 */
class Comment
{
    /**
     * @var bigint $comment_ID
     *
     * @ORM\Column(name="comment_ID", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $comment_ID;

    /**
     * @var text $comment_author
     *
     * @ORM\Column(name="comment_author", type="text")
     */
    private $comment_author;

    /**
     * @var string $comment_author_email
     *
     * @ORM\Column(name="comment_author_email", type="string")
     */
    private $comment_author_email;

    /**
     * @var string $comment_author_url
     *
     * @ORM\Column(name="comment_author_url", type="string")
     */
    private $comment_author_url;

    /**
     * @var string $comment_author_IP
     *
     * @ORM\Column(name="comment_author_IP", type="string")
     */
    private $comment_author_IP;

    /**
     * @var datetime $comment_date
     *
     * @ORM\Column(name="comment_date", type="datetime")
     */
    private $comment_date;

    /**
     * @var datetime $comment_date_gmt
     *
     * @ORM\Column(name="comment_date_gmt", type="datetime")
     */
    private $comment_date_gmt;

    /**
     * @var text $comment_content
     *
     * @ORM\Column(name="comment_content", type="text")
     */
    private $comment_content;

    /**
     * @var integer $comment_karma
     *
     * @ORM\Column(name="comment_karma", type="integer")
     */
    private $comment_karma;

    /**
     * @var string $comment_approved
     *
     * @ORM\Column(name="comment_approved", type="string")
     */
    private $comment_approved;

    /**
     * @var string $comment_agent
     *
     * @ORM\Column(name="comment_agent", type="string")
     */
    private $comment_agent;

    /**
     * @var string $comment_type
     *
     * @ORM\Column(name="comment_type", type="string")
     */
    private $comment_type;

    /**
     * @var bigint $comment_parent
     *
     * @ORM\Column(name="comment_parent", type="bigint")
     */
    private $comment_parent;

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
     *   @ORM\JoinColumn(name="comment_post_ID", referencedColumnName="ID")
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
        $this->metas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get comment_ID
     *
     * @return bigint 
     */
    public function getCommentID()
    {
        return $this->comment_ID;
    }

    /**
     * Set comment_author
     *
     * @param text $commentAuthor
     */
    public function setCommentAuthor($commentAuthor)
    {
        $this->comment_author = $commentAuthor;
    }

    /**
     * Get comment_author
     *
     * @return text 
     */
    public function getCommentAuthor()
    {
        return $this->comment_author;
    }

    /**
     * Set comment_author_email
     *
     * @param string $commentAuthorEmail
     */
    public function setCommentAuthorEmail($commentAuthorEmail)
    {
        $this->comment_author_email = $commentAuthorEmail;
    }

    /**
     * Get comment_author_email
     *
     * @return string 
     */
    public function getCommentAuthorEmail()
    {
        return $this->comment_author_email;
    }

    /**
     * Set comment_author_url
     *
     * @param string $commentAuthorUrl
     */
    public function setCommentAuthorUrl($commentAuthorUrl)
    {
        $this->comment_author_url = $commentAuthorUrl;
    }

    /**
     * Get comment_author_url
     *
     * @return string 
     */
    public function getCommentAuthorUrl()
    {
        return $this->comment_author_url;
    }

    /**
     * Set comment_author_IP
     *
     * @param string $commentAuthorIP
     */
    public function setCommentAuthorIP($commentAuthorIP)
    {
        $this->comment_author_IP = $commentAuthorIP;
    }

    /**
     * Get comment_author_IP
     *
     * @return string 
     */
    public function getCommentAuthorIP()
    {
        return $this->comment_author_IP;
    }

    /**
     * Set comment_date
     *
     * @param datetime $commentDate
     */
    public function setCommentDate($commentDate)
    {
        $this->comment_date = $commentDate;
    }

    /**
     * Get comment_date
     *
     * @return datetime 
     */
    public function getCommentDate()
    {
        return $this->comment_date;
    }

    /**
     * Set comment_date_gmt
     *
     * @param datetime $commentDateGmt
     */
    public function setCommentDateGmt($commentDateGmt)
    {
        $this->comment_date_gmt = $commentDateGmt;
    }

    /**
     * Get comment_date_gmt
     *
     * @return datetime 
     */
    public function getCommentDateGmt()
    {
        return $this->comment_date_gmt;
    }

    /**
     * Set comment_content
     *
     * @param text $commentContent
     */
    public function setCommentContent($commentContent)
    {
        $this->comment_content = $commentContent;
    }

    /**
     * Get comment_content
     *
     * @return text 
     */
    public function getCommentContent()
    {
        return $this->comment_content;
    }

    /**
     * Set comment_karma
     *
     * @param integer $commentKarma
     */
    public function setCommentKarma($commentKarma)
    {
        $this->comment_karma = $commentKarma;
    }

    /**
     * Get comment_karma
     *
     * @return integer 
     */
    public function getCommentKarma()
    {
        return $this->comment_karma;
    }

    /**
     * Set comment_approved
     *
     * @param string $commentApproved
     */
    public function setCommentApproved($commentApproved)
    {
        $this->comment_approved = $commentApproved;
    }

    /**
     * Get comment_approved
     *
     * @return string 
     */
    public function getCommentApproved()
    {
        return $this->comment_approved;
    }

    /**
     * Set comment_agent
     *
     * @param string $commentAgent
     */
    public function setCommentAgent($commentAgent)
    {
        $this->comment_agent = $commentAgent;
    }

    /**
     * Get comment_agent
     *
     * @return string 
     */
    public function getCommentAgent()
    {
        return $this->comment_agent;
    }

    /**
     * Set comment_type
     *
     * @param string $commentType
     */
    public function setCommentType($commentType)
    {
        $this->comment_type = $commentType;
    }

    /**
     * Get comment_type
     *
     * @return string 
     */
    public function getCommentType()
    {
        return $this->comment_type;
    }

    /**
     * Set comment_parent
     *
     * @param bigint $commentParent
     */
    public function setCommentParent($commentParent)
    {
        $this->comment_parent = $commentParent;
    }

    /**
     * Get comment_parent
     *
     * @return bigint 
     */
    public function getCommentParent()
    {
        return $this->comment_parent;
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