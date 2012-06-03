<?php

namespace Hypebeast\WordpressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hypebeast\WordpressBundle\Entity\Option
 *
 * @ORM\Table(name="wp_options")
 * @ORM\Entity
 */
class Option
{
    /**
     * @var bigint $option_id
     *
     * @ORM\Column(name="option_id", type="bigint", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $option_id;

    /**
     * @var integer $blog_id
     *
     * @ORM\Column(name="blog_id", type="integer", length=11, nullable=false)
     */
    private $blog_id;

    /**
     * @var string $option_name
     *
     * @ORM\Column(name="option_name", type="string", length=64, nullable=false, unique=true)
     */
    private $option_name;

    /**
     * @var text $option_value
     *
     * @ORM\Column(name="option_value", type="text", nullable=false)
     */
    private $option_value;

    /**
     * @var string $autoload
     *
     * @ORM\Column(name="autoload", type="string", length=20, nullable=false)
     */
    private $autoload;


    /**
     * Get option_id
     *
     * @return bigint 
     */
    public function getOptionId()
    {
        return $this->option_id;
    }

    /**
     * Set blog_id
     *
     * @param integer $blogId
     */
    public function setBlogId($blogId)
    {
        $this->blog_id = $blogId;
    }

    /**
     * Get blog_id
     *
     * @return integer 
     */
    public function getBlogId()
    {
        return $this->blog_id;
    }

    /**
     * Set option_name
     *
     * @param string $optionName
     */
    public function setOptionName($optionName)
    {
        $this->option_name = $optionName;
    }

    /**
     * Get option_name
     *
     * @return string 
     */
    public function getOptionName()
    {
        return $this->option_name;
    }

    /**
     * Set option_value
     *
     * @param text $optionValue
     */
    public function setOptionValue($optionValue)
    {
        $this->option_value = $optionValue;
    }

    /**
     * Get option_value
     *
     * @return text 
     */
    public function getOptionValue()
    {
        return $this->option_value;
    }

    /**
     * Set autoload
     *
     * @param string $autoload
     */
    public function setAutoload($autoload)
    {
        $this->autoload = $autoload;
    }

    /**
     * Get autoload
     *
     * @return string 
     */
    public function getAutoload()
    {
        return $this->autoload;
    }
}