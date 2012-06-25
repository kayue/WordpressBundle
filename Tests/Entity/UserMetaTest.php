<?php

namespace Hypebeast\WordpressBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hypebeast\WordpressBundle\Entity\User;
use Hypebeast\WordpressBundle\Entity\UserMeta;

class UserMetaTest extends WebTestCase
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

        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->em->getConnection()->beginTransaction();
    }

    protected function tearDown()
    {
        $this->em->getConnection()->rollback();

        parent::tearDown();
    }

    public function testNewMeta()
    {
        $meta = new UserMeta();
        $meta->setKey('the_meta_key');
        $meta->setValue('the_meta_value');

        $user = $this->getUserRepository()->find(1);
        $user->addMeta($meta);

        $this->em->persist($user);
        $this->em->flush();

        $user = $this->getUserRepository()->find(1);

        $this->assertEquals($meta, $user->getMetasByKey('the_meta_key')->first());
        $this->assertEquals('the_meta_value', $user->getMetasByKey('the_meta_key')->first()->getValue());
    }

    public function testReadSerializedMeta()
    {
        $user = $this->getUserRepository()->find(1);
        $meta = $user->getMetasByKey('wp_capabilities')->first();

        // test
        $this->assertArrayHasKey('administrator', $meta->getValue());
    }

    public function testUpdateSerializedMeta()
    {
        $user = $this->getUserRepository()->find(1);
        $meta = $user->getMetasByKey('wp_capabilities')->first();

        $meta->setValue(array('administrator' => '2'));

        $this->em->persist($user);
        $this->em->flush();

        // test
        $user = $this->getUserRepository()->find(1);
        $array = $user->getMetasByKey('wp_capabilities')->first()->getValue();
        $this->assertEquals(2, $array['administrator']);
    }

    public function testCreateArrayMeta()
    {
        $meta = new UserMeta();
        $meta->setKey('the_meta_key');
        $meta->setValue(array('the key' => 'the value'));

        $user = $this->getUserRepository()->find(1);
        $user->addMeta($meta);

        $this->em->persist($user);
        $this->em->flush();

        // test
        $user = $this->getUserRepository()->find(1);
        $this->assertArrayHasKey('the key', $user->getMetasByKey('the_meta_key')->first()->getValue());
    }

    protected function getUserRepository()
    {
        return $this->em->getRepository('HypebeastWordpressBundle:User');
    }
}