<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\Taxonomy
 *
 * @ORM\Table(name="term_taxonomy")
 * @ORM\Entity
 */
class Taxonomy
{
    /**
     * @var bigint $id
     *
     * @ORM\Column(name="term_taxonomy_id", type="wordpressid", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="taxonomy", type="string", length=32)
     */
    private $name;

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
     * @ORM\OneToOne(targetEntity="Hypebeast\WordpressBundle\Entity\Term", inversedBy="taxonomy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="term_id", referencedColumnName="term_id", unique=true)
     * })
     */
    private $term;


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