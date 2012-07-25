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

    private function createTestPost($path, $fileName, $ext, $width, $height, $sizes){
        $post = new Post();
        $post->setTitle(rand());
        $post->setContent(rand());
        $this->em->persist($post);
        $this->createTestAttachment($path, $fileName, $ext, $post, $width, $height, $sizes);

        $this->em->flush();

        return $post;
    }

    public function testGetThumbnail()
    {
        $post = $this->createTestPost('http://www.example.com/', 'file', 'jpg',  
            600, 400, array(array(600, 400), array(300, 200), array(200, 150)));

        $result = $this->instance->getThumbnail($post);

        $this->assertEquals('http://www.example.com/file-300x200.jpg', $result);
    }


}