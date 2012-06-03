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
        $user = $userRepository->find(1);

        $post = new Post();
        $post->setPostName('Test');
        $post->setPostDate(new \DateTime());
        $post->setPostDateGmt(new \DateTime());
        $post->setPostModified(new \DateTime());
        $post->setPostModifiedGmt(new \DateTime());
        $post->setPostContent('Content');
        $post->setPostTitle('Title');
        $post->setPostExcerpt('setPostExcerpt');
        $post->setGuid('http://127.0.0.1/wordpress/?p=5');
        $post->setUser($user);

        $this->em->persist($post);
        $this->em->flush();

        $this->assertEquals('Title', $post->getPostTitle());

        $post->setPostTitle('New Title');
        $this->em->flush();

        $this->assertEquals('New Title', $post->getPostTitle());

        // there should be one more post in the result.
        $this->assertCount($count + 1, $postRepository->findAll());
    }
}