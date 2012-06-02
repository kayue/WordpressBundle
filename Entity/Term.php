<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\Term
 *
 * @ORM\Table(name="wp_terms")
 * @ORM\Entity
 */
class Term
{
    /**
     * @var bigint $term_id
     *
     * @ORM\Column(name="term_id", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $term_id;

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
     * @var bigint $term_group
     *
     * @ORM\Column(name="term_group", type="bigint", length=10)
     */
    private $term_group;


    /**
     * Get term_id
     *
     * @return bigint 
     */
    public function getTermId()
    {
        return $this->term_id;
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
     * Set term_group
     *
     * @param bigint $termGroup
     */
    public function setTermGroup($termGroup)
    {
        $this->term_group = $termGroup;
    }

    /**
     * Get term_group
     *
     * @return bigint 
     */
    public function getTermGroup()
    {
        return $this->term_group;
    }
}