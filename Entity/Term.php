<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\Term
 *
 * @ORM\Table(name="terms")
 * @ORM\Entity
 */
class Term
{
    /**
     * @var bigint $id
     *
     * @ORM\Column(name="term_id", type="wordpressid", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=200)
     */
    private $slug;

    /**
     * @var bigint $group
     *
     * @ORM\Column(name="term_group", type="bigint", length=10)
     */
    private $group;

    /**
     * @var Hypebeast\WordpressBundle\Entity\Taxonomy
     *
     * @ORM\OneToOne(targetEntity="Taxonomy", mappedBy="term")
     **/
    private $taxonomy;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set group
     *
     * @param bigint $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return bigint
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set taxonomy
     *
     * @param Hypebeast\WordpressBundle\Entity\Taxonomy $taxonomy
     */
    public function setTaxonomy(\Hypebeast\WordpressBundle\Entity\Taxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * Get taxonomy
     *
     * @return Hypebeast\WordpressBundle\Entity\Taxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }
}