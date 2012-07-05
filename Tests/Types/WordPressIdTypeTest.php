<?php

namespace Hypebeast\WordpressBundle\Tests\Types;

use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hypebeast\WordpressBundle\Types\WordPressIdType;

class WordPressUserTypeTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    protected function setUp()
    {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();
    }

    public function testAddType()
    {
        // add a custom database type for WordPress's ID columns
        if (!Type::hasType(WordPressIdType::NAME)) {
            Type::addType(WordPressIdType::NAME, 'Hypebeast\WordpressBundle\Types\WordPressIdType');
        }

        $this->assertTrue(Type::hasType(WordPressIdType::NAME), 'Custom type "'+WordPressIdType::NAME+'" doesn\'t not exist.');
    }
}