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

    /**
     * @dataProvider postProvider
     */
    public function testNewPost($title, $content, $userId)
    {
        $post = new Post();
        $post->setPostTitle($title);
        $post->setPostName('Test');
        $post->setPostContent($content);
        $post->setPostExcerpt('setPostExcerpt');
        $post->setUser($this->getUserRepository()->find($userId));

        $this->em->persist($post);
        $this->em->flush();

        $result = $this->getPostRepository()
            ->createQueryBuilder('post')
            ->orderBy('post.ID', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($title, $result->getPostTitle());
        $this->assertEquals($content, $result->getPostContent());
        $this->assertEquals($userId, $result->getUser()->getId());
    }

    /**
     * @depends testNewPost
     */
    public function testUpdatePost($post)
    {
        echo $post->getPostTitle();

        $this->assertTrue(1);
    }

    public function postProvider()
    {
        return array(
            array('Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', 1),
            array('Sed ut perspiciatis unde', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.', 1)
        );
    }

    // public function testFindAll()
    // {
    //     $count = count($this->getPostRepository()->findAll());

    //     // get the newly added post for tests
    //     $post = $postRepository->find($post->getId());

    //     $this->assertCount($count + 1, $postRepository->findAll());
    //     $this->assertEquals('Title', $post->getPostTitle());

    //     // test update        
    //     $post->setPostTitle('New Title');
    //     $this->em->flush();

    //     $post = $postRepository->find($post->getId());
    //     $this->assertEquals('New Title', $post->getPostTitle());
    // }

    protected function getPostRepository()
    {
        return $this->em->getRepository('HypebeastWordpressBundle:Post');
    }

    protected function getUserRepository()
    {
        return $this->em->getRepository('HypebeastWordpressBundle:User');
    }
}