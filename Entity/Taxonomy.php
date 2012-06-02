<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\Taxonomy
 *
 * @ORM\Table(name="wp_term_taxonomy")
 * @ORM\Entity
 */
class Taxonomy
{
    /**
     * @var bigint $term_taxonomy_id
     *
     * @ORM\Column(name="term_taxonomy_id", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $term_taxonomy_id;

    /**
     * @var string $taxonomy
     *
     * @ORM\Column(name="taxonomy", type="string", length=32)
     */
    private $taxonomy;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bigint $parent
     *
     * @ORM\Column(name="parent", type="bigint", length=20)
     */
    private $parent;

    /**
     * @var bigint $count
     *
     * @ORM\Column(name="count", type="bigint", length=20)
     */
    private $count;

    /**
     * @var Hypebeast\WordpressBundle\Entity\Term
     *
     * @ORM\OneToOne(targetEntity="Hypebeast\WordpressBundle\Entity\Term")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="term_id", referencedColumnName="term_id", unique=true)
     * })
     */
    private $term;


    /**
     * Get term_taxonomy_id
     *
     * @return bigint 
     */
    public function getTermTaxonomyId()
    {
        return $this->term_taxonomy_id;
    }

    /**
     * Set taxonomy
     *
     * @param string $taxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * Get taxonomy
     *
     * @return string 
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set count
     *
     * @param bigint $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * Get count
     *
     * @return bigint 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set term
     *
     * @param Hypebeast\WordpressBundle\Entity\Term $term
     */
    public function setTerm(\Hypebeast\WordpressBundle\Entity\Term $term)
    {
        $this->term = $term;
    }

    /**
     * Get term
     *
     * @return Hypebeast\WordpressBundle\Entity\Term 
     */
    public function getTerm()
    {
        return $this->term;
    }
}