<?php

namespace Hypebeast\WordpressBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hypebeast\WordpressBundle\Entity\Post;
use Hypebeast\WordpressBundle\Entity\User;

class PostTest extends WebTestCase
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

    public function testFindAll()
    {
        $postRepository = $this->em->getRepository('HypebeastWordpressBundle:Post');
        $userRepository = $this->em->getRepository('HypebeastWordpressBundle:User');
        
        $count = count($postRepository->findAll());

        $post = new Post();
        $post->setPostName('Test');
        $post->setPostContent('Content');
        $post->setPostTitle('Title');
        $post->setPostExcerpt('setPostExcerpt');
        $post->setUser($userRepository->find(1));

        $this->em->persist($post);
        $this->em->flush();

        // get the newly added post for tests
        $post = $postRepository->find($post->getId());

        $this->assertCount($count + 1, $postRepository->findAll());
        $this->assertEquals('Title', $post->getPostTitle());

        // test update        
        $post->setPostTitle('New Title');
        $this->em->flush();

        $post = $postRepository->find($post->getId());
        $this->assertEquals('New Title', $post->getPostTitle());
    }
}