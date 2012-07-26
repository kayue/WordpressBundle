<?php

namespace Hypebeast\WordpressBundle\Tests\Extensions;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hypebeast\WordpressBundle\Extensions\WordpressTwigExtension;
use Hypebeast\WordpressBundle\Entity\Post;
use Hypebeast\WordpressBundle\Entity\PostMeta;

class WordpressTwigExtensionTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $instance;

    protected function setUp()
    {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();

        $this->em = $kernel->getContainer()->get('doctrine')->getEntityManager();

        $this->em->getConnection()->beginTransaction();

        $this->instance = new WordpressTwigExtension($kernel->getContainer()->get('doctrine'));
    }

    protected function tearDown()
    {
        $this->em->getConnection()->rollback();

        parent::tearDown();
    }

    private function createSizeArray($w, $h, $sizes, $fileName, $ext){
        $sizeArray = array();
        foreach ($sizes as $size){
            $sizeArray[] = array('file' => $fileName.'-'.$size[0].'x'.$size[1].'.'.$ext, 'width' => $size[0], 'height' => $size[1]);
        }

        return array('width' => $w, 'height' => $h, 'hwstring_small' => "height='".$h."' width='".$w.'"',
            'file' => $fileName, 'sizes' => $sizeArray);
    }

    private function createTestAttachment($path, $fileName, $ext, $post, $w, $h, $sizes, $mimeType = 'image/jpeg'){
        $attach = new Post();
        $attach->setTitle($fileName);
        $attach->setContent($fileName);
        $attach->setStatus('inherit');
        $attach->setParent($post);
        $post->addChild($attach);
        $attach->setType('attachment');
        $attach->setMimeType($mimeType);
        $attach->setGuid($path . $fileName . $ext);

        $meta = new PostMeta();
        $meta->setKey('_wp_attachment_metadata');
        $meta->setValue($this->createSizeArray($w, $h, $sizes, $fileName, $ext));
        $attach->addMeta($meta);

        $this->em->persist($meta);
        $this->em->persist($attach);

        $this->em->flush();
    }

    private function createTestPost($attachments){
        $post = new Post();
        $post->setTitle(rand());
        $post->setContent(rand());
        $this->em->persist($post);

        foreach ($attachments as $attachment){
            if (empty($attachment['mimeType'])){
                $attachment['mimeType'] = 'image/jpeg';
            }
            $this->createTestAttachment($attachment['path'], $attachment['fileName'], $attachment['ext'], 
                $post, $attachment['width'], $attachment['height'], $attachment['sizes'], $attachment['mimeType']);
        }

        $this->em->flush();

        return $post;
    }

    public function testGetPostThumbnail()
    {
        //contain exact size of thumbnail 300x200
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 600, 
                'height' => 400, 
                'sizes' => array(array(600, 400), array(300, 200), array(200, 150))
            )
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertEquals('http://www.example.com/file-300x200.jpg', $result);

        //standard input
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 800, 
                'height' => 600, 
                'sizes' => array(array(800, 600), array(600, 400), array(400, 200), array(200, 100))
            )
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertEquals('http://www.example.com/file-400x200.jpg', $result);

        //all larger than thumbnail
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 1024, 
                'height' => 768, 
                'sizes' => array(array(1024, 768), array(800, 600), array(640, 480), array(480, 320))
            )
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertEquals('http://www.example.com/file-480x320.jpg', $result);

        //all smaller than thumbnail
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 120, 
                'height' => 120, 
                'sizes' => array(array(120, 120), array(60, 60), array(30, 30))
            )
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertEquals('http://www.example.com/file-120x120.jpg', $result);

        //only one sizes
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 1024, 
                'height' => 768, 
                'sizes' => array(array(1024, 768))
            )
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertEquals('http://www.example.com/file-1024x768.jpg', $result);

        //no sizes at all
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 1024, 
                'height' => 768, 
                'sizes' => array()
            )
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertNull($result);

        //test for file name, paths too.
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.google.com/', 
                'fileName' => 'upload', 
                'ext' => 'png',  
                'width' => 800, 
                'height' => 600, 
                'sizes' => array(array(800, 600), array(600, 400), array(400, 200), array(200, 100)),
                'mimeType' => 'image/png'
             )
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertEquals('http://www.google.com/upload-400x200.png', $result);


    }


}