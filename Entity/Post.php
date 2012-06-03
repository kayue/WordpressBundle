<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\Post
 *
 * @ORM\Table(name="wp_posts")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @var bigint $ID
     *
     * @ORM\Column(name="ID", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ID;

    /**
     * @var datetime $post_date
     *
     * @ORM\Column(name="post_date", type="datetime", nullable=false)
     */
    private $post_date;

    /**
     * @var datetime $post_date_gmt
     *
     * @ORM\Column(name="post_date_gmt", type="datetime", nullable=false)
     */
    private $post_date_gmt;

    /**
     * @var text $post_content
     *
     * @ORM\Column(name="post_content", type="text", nullable=false)
     */
    private $post_content;

    /**
     * @var text $post_title
     *
     * @ORM\Column(name="post_title", type="text", nullable=false)
     */
    private $post_title;

    /**
     * @var text $post_excerpt
     *
     * @ORM\Column(name="post_excerpt", type="text", nullable=false)
     */
    private $post_excerpt;

    /**
     * @var string $post_status
     *
     * @ORM\Column(name="post_status", type="string", length=20, nullable=false)
     */
    private $post_status = "publish";

    /**
     * @var string $comment_status
     *
     * @ORM\Column(name="comment_status", type="string", length=20, nullable=false)
     */
    private $comment_status = "open";

    /**
     * @var string $ping_status
     *
     * @ORM\Column(name="ping_status", type="string", length=20, nullable=false)
     */
    private $ping_status = "open";

    /**
     * @var string $post_password
     *
     * @ORM\Column(name="post_password", type="string", length=20, nullable=false)
     */
    private $post_password = "";

    /**
     * @var string $post_name
     *
     * @ORM\Column(name="post_name", type="string", length=200, nullable=false)
     */
    private $post_name;

    /**
     * @var text $to_ping
     *
     * @ORM\Column(name="to_ping", type="text", nullable=false)
     */
    private $to_ping = "";

    /**
     * @var text $pinged
     *
     * @ORM\Column(name="pinged", type="text", nullable=false)
     */
    private $pinged = "";

    /**
     * @var datetime $post_modified
     *
     * @ORM\Column(name="post_modified", type="datetime", nullable=false)
     */
    private $post_modified;

    /**
     * @var datetime $post_modified_gmt
     *
     * @ORM\Column(name="post_modified_gmt", type="datetime", nullable=false)
     */
    private $post_modified_gmt;

    /**
     * @var text $post_content_filtered
     *
     * @ORM\Column(name="post_content_filtered", type="text", nullable=false)
     */
    private $post_content_filtered = "";

    /**
     * @var bigint $post_parent
     *
     * @ORM\Column(name="post_parent", type="bigint", nullable=false)
     */
    private $post_parent = 0;

    /**
     * @var string $guid
     *
     * @ORM\Column(name="guid", type="string", length=255, nullable=false)
     */
    private $guid = "";

    /**
     * @var integer $menu_order
     *
     * @ORM\Column(name="menu_order", type="integer", length=11, nullable=false)
     */
    private $menu_order = 0;

    /**
     * @var string $post_type
     *
     * @ORM\Column(name="post_type", type="string", nullable=false)
     */
    private $post_type = "post";

    /**
     * @var string $post_mime_type
     *
     * @ORM\Column(name="post_mime_type", type="string", length=100, nullable=false)
     */
    private $post_mime_type = "";

    /**
     * @var bigint $comment_count
     *
     * @ORM\Column(name="comment_count", type="bigint", length=20, nullable=false)
     */
    private $comment_count = 0;

    /**
     * @var Hypebeast\WordpressBundle\Entity\PostMeta
     *
     * @ORM\OneToMany(targetEntity="Hypebeast\WordpressBundle\Entity\PostMeta", mappedBy="post")
     */
    private $metas;

    /**
     * @var Hypebeast\WordpressBundle\Entity\Comment
     *
     * @ORM\OneToMany(targetEntity="Hypebeast\WordpressBundle\Entity\Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @var Hypebeast\WordpressBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Hypebeast\WordpressBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_author", referencedColumnName="ID")
     * })
     */
    private $user;

    /**
     * @var Hypebeast\WordpressBundle\Entity\Taxonomy
     *
     * @ORM\ManyToMany(targetEntity="Hypebeast\WordpressBundle\Entity\Taxonomy")
     * @ORM\JoinTable(name="wp_term_relationships",
     *   joinColumns={
     *     @ORM\JoinColumn(name="object_id", referencedColumnName="ID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="term_taxonomy_id", referencedColumnName="term_taxonomy_id")
     *   }
     * )
     */
    private $taxonomies;

    public function __construct()
    {
        $this->metas      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->taxonomies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist() 
    {
        $this->post_date         = new \DateTime('now');
        $this->post_date_gmt     = new \DateTime('now');
        $this->post_modified     = new \DateTime('now');
        $this->post_modified_gmt = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate() 
    {
        $this->post_modified     = new \DateTime('now');
        $this->post_modified_gmt = new \DateTime('now');
    }

    /**
     * Get ID
     *
     * @return bigint 
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * Set post_date
     *
     * @param datetime $postDate
     */
    public function setPostDate($postDate)
    {
        $this->post_date = $postDate;
    }

    /**
     * Get post_date
     *
     * @return datetime 
     */
    public function getPostDate()
    {
        return $this->post_date;
    }

    /**
     * Set post_date_gmt
     *
     * @param datetime $postDateGmt
     */
    public function setPostDateGmt($postDateGmt)
    {
        $this->post_date_gmt = $postDateGmt;
    }

    /**
     * Get post_date_gmt
     *
     * @return datetime 
     */
    public function getPostDateGmt()
    {
        return $this->post_date_gmt;
    }

    /**
     * Set post_content
     *
     * @param text $postContent
     */
    public function setPostContent($postContent)
    {
        $this->post_content = $postContent;
    }

    /**
     * Get post_content
     *
     * @return text 
     */
    public function getPostContent()
    {
        return $this->post_content;
    }

    /**
     * Set post_title
     *
     * @param text $postTitle
     */
    public function setPostTitle($postTitle)
    {
        $this->post_title = $postTitle;
    }

    /**
     * Get post_title
     *
     * @return text 
     */
    public function getPostTitle()
    {
        return $this->post_title;
    }

    /**
     * Set post_excerpt
     *
     * @param text $postExcerpt
     */
    public function setPostExcerpt($postExcerpt)
    {
        $this->post_excerpt = $postExcerpt;
    }

    /**
     * Get post_excerpt
     *
     * @return text 
     */
    public function getPostExcerpt()
    {
        return $this->post_excerpt;
    }

    /**
     * Set post_status
     *
     * @param string $postStatus
     */
    public function setPostStatus($postStatus)
    {
        $this->post_status = $postStatus;
    }

    /**
     * Get post_status
     *
     * @return string 
     */
    public function getPostStatus()
    {
        return $this->post_status;
    }

    /**
     * Set comment_status
     *
     * @param string $commentStatus
     */
    public function setCommentStatus($commentStatus)
    {
        $this->comment_status = $commentStatus;
    }

    /**
     * Get comment_status
     *
     * @return string 
     */
    public function getCommentStatus()
    {
        return $this->comment_status;
    }

    /**
     * Set ping_status
     *
     * @param string $pingStatus
     */
    public function setPingStatus($pingStatus)
    {
        $this->ping_status = $pingStatus;
    }

    /**
     * Get ping_status
     *
     * @return string 
     */
    public function getPingStatus()
    {
        return $this->ping_status;
    }

    /**
     * Set post_password
     *
     * @param string $postPassword
     */
    public function setPostPassword($postPassword)
    {
        $this->post_password = $postPassword;
    }

    /**
     * Get post_password
     *
     * @return string 
     */
    public function getPostPassword()
    {
        return $this->post_password;
    }

    /**
     * Set post_name
     *
     * @param string $postName
     */
    public function setPostName($postName)
    {
        $this->post_name = $postName;
    }

    /**
     * Get post_name
     *
     * @return string 
     */
    public function getPostName()
    {
        return $this->post_name;
    }

    /**
     * Set to_ping
     *
     * @param text $toPing
     */
    public function setToPing($toPing)
    {
        $this->to_ping = $toPing;
    }

    /**
     * Get to_ping
     *
     * @return text 
     */
    public function getToPing()
    {
        return $this->to_ping;
    }

    /**
     * Set pinged
     *
     * @param text $pinged
     */
    public function setPinged($pinged)
    {
        $this->pinged = $pinged;
    }

    /**
     * Get pinged
     *
     * @return text 
     */
    public function getPinged()
    {
        return $this->pinged;
    }

    /**
     * Set post_modified
     *
     * @param datetime $postModified
     */
    public function setPostModified($postModified)
    {
        $this->post_modified = $postModified;
    }

    /**
     * Get post_modified
     *
     * @return datetime 
     */
    public function getPostModified()
    {
        return $this->post_modified;
    }

    /**
     * Set post_modified_gmt
     *
     * @param datetime $postModifiedGmt
     */
    public function setPostModifiedGmt($postModifiedGmt)
    {
        $this->post_modified_gmt = $postModifiedGmt;
    }

    /**
     * Get post_modified_gmt
     *
     * @return datetime 
     */
    public function getPostModifiedGmt()
    {
        return $this->post_modified_gmt;
    }

    /**
     * Set post_content_filtered
     *
     * @param text $postContentFiltered
     */
    public function setPostContentFiltered($postContentFiltered)
    {
        $this->post_content_filtered = $postContentFiltered;
    }

    /**
     * Get post_content_filtered
     *
     * @return text 
     */
    public function getPostContentFiltered()
    {
        return $this->post_content_filtered;
    }

    /**
     * Set post_parent
     *
     * @param bigint $postParent
     */
    public function setPostParent($postParent)
    {
        $this->post_parent = $postParent;
    }

    /**
     * Get post_parent
     *
     * @return bigint 
     */
    public function getPostParent()
    {
        return $this->post_parent;
    }

    /**
     * Set guid
     *
     * @param string $guid
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set menu_order
     *
     * @param integer $menuOrder
     */
    public function setMenuOrder($menuOrder)
    {
        $this->menu_order = $menuOrder;
    }

    /**
     * Get menu_order
     *
     * @return integer 
     */
    public function getMenuOrder()
    {
        return $this->menu_order;
    }

    /**
     * Set post_type
     *
     * @param string $postType
     */
    public function setPostType($postType)
    {
        $this->post_type = $postType;
    }

    /**
     * Get post_type
     *
     * @return string 
     */
    public function getPostType()
    {
        return $this->post_type;
    }

    /**
     * Set post_mime_type
     *
     * @param string $postMimeType
     */
    public function setPostMimeType($postMimeType)
    {
        $this->post_mime_type = $postMimeType;
    }

    /**
     * Get post_mime_type
     *
     * @return string 
     */
    public function getPostMimeType()
    {
        return $this->post_mime_type;
    }

    /**
     * Set comment_count
     *
     * @param bigint $commentCount
     */
    public function setCommentCount($commentCount)
    {
        $this->comment_count = $commentCount;
    }

    /**
     * Get comment_count
     *
     * @return bigint 
     */
    public function getCommentCount()
    {
        return $this->comment_count;
    }

    /**
     * Add metas
     *
     * @param Hypebeast\WordpressBundle\Entity\PostMeta $metas
     */
    public function addPostMeta(\Hypebeast\WordpressBundle\Entity\PostMeta $metas)
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
     * Add comments
     *
     * @param Hypebeast\WordpressBundle\Entity\Comment $comments
     */
    public function addComment(\Hypebeast\WordpressBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
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

    /**
     * Add taxonomies
     *
     * @param Hypebeast\WordpressBundle\Entity\Taxonomy $taxonomies
     */
    public function addTaxonomy(\Hypebeast\WordpressBundle\Entity\Taxonomy $taxonomies)
    {
        $this->taxonomies[] = $taxonomies;
    }

    /**
     * Get taxonomies
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTaxonomies()
    {
        return $this->taxonomies;
    }
}