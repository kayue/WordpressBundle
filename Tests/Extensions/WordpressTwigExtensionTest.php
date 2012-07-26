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

    private function createTestAttachment($path, $fileName, $ext, $post, $w, $h, $sizes, $feature = false, $mimeType = 'image/jpeg'){
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
        $meta->setPost($post);
        $attach->addMeta($meta);

        $this->em->persist($meta);
        $this->em->persist($attach);

        $this->em->flush();

        if ($feature){
            $featureMeta = new PostMeta();
            $featureMeta->setKey('_thumbnail_id');
            $featureMeta->setValue($attach->getId());
            $featureMeta->setPost($post);
            $post->addMeta($featureMeta);
            $this->em->persist($featureMeta);
        }

        $this->em->flush();

        return $attach;
    }

    private function createTestPost($attachments){
        $post = new Post();
        $post->setTitle(rand());
        $post->setContent(rand());
        $this->em->persist($post);

        foreach ($attachments as $attachment){
            if (empty($attachment['feature'])){
                $attachment['feature'] = false;
            }
            if (empty($attachment['mimeType'])){
                $attachment['mimeType'] = 'image/jpeg';
            }
            $this->createTestAttachment($attachment['path'], $attachment['fileName'], $attachment['ext'], 
                $post, $attachment['width'], $attachment['height'], $attachment['sizes'], $attachment['feature'], $attachment['mimeType']);
        }

        $this->em->flush();

        return $post;
    }

    public function testGetThumbnail()
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

        //no attachment at all
        $post = $this->createTestPost(array());
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

        //more than one attachment, no feature
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file1', 
                'ext' => 'jpg',  
                'width' => 1024, 
                'height' => 768, 
                'sizes' => array(array(1024, 768))
            ),
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file2', 
                'ext' => 'jpg',  
                'width' => 800, 
                'height' => 600, 
                'sizes' => array(array(800, 600))
            ),
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertEquals('http://www.example.com/file1-1024x768.jpg', $result);

        //more than one attachment, contains feature
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file1', 
                'ext' => 'jpg',  
                'width' => 1024, 
                'height' => 768, 
                'sizes' => array(array(1024, 768))
            ),
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file2', 
                'ext' => 'jpg',  
                'width' => 800, 
                'height' => 600, 
                'sizes' => array(array(800, 600)),
                'feature' => true
            ),
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file3', 
                'ext' => 'jpg',  
                'width' => 300, 
                'height' => 200, 
                'sizes' => array(array(300, 200))
            ),
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertEquals('http://www.example.com/file2-800x600.jpg', $result);

        //check whether the upload is an image
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'pdf',  
                'width' => 1024, 
                'height' => 768, 
                'sizes' => array(array(1024, 768)),
                'mimeType' => 'application/pdf'
            )
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertNull($result);

        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file1', 
                'ext' => 'txt',  
                'width' => 1024, 
                'height' => 768, 
                'sizes' => array(array(1024, 768)),
                'mimeType' => 'text/plain'
            ),
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file2', 
                'ext' => 'mpg',  
                'width' => 800, 
                'height' => 600, 
                'sizes' => array(array(800, 600)),
                'mimeType' => 'audio/mpeg'
            ),
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertNull($result);

        //mix image and non-image!
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file1', 
                'ext' => 'txt',  
                'width' => 1024, 
                'height' => 768, 
                'sizes' => array(array(1024, 768)),
                'mimeType' => 'text/plain'
            ),
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file2', 
                'ext' => 'mpg',  
                'width' => 800, 
                'height' => 600, 
                'sizes' => array(array(800, 600)),
                'mimeType' => 'audio/mpeg'
            ),
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file3', 
                'ext' => 'jpg',  
                'width' => 1024, 
                'height' => 768, 
                'sizes' => array(array(1024, 768))
            ),
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file4', 
                'ext' => 'jpg',  
                'width' => 800, 
                'height' => 600, 
                'sizes' => array(array(800, 600))
            ),
        ));
        $result = $this->instance->getThumbnail($post);
        $this->assertEquals('http://www.example.com/file3-1024x768.jpg', $result);

        //test custom thumbnail size
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 240, 
                'height' => 120, 
                'sizes' => array(array(240, 120), array(120, 60), array(60, 30))
            )
        ));
        $result = $this->instance->getThumbnail($post, array(120, 60));
        $this->assertEquals('http://www.example.com/file-120x60.jpg', $result);

        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 240, 
                'height' => 120, 
                'sizes' => array(array(240, 120), array(120, 60), array(60, 30))
            )
        ));
        $result = $this->instance->getThumbnail($post, array(90, 60));
        $this->assertEquals('http://www.example.com/file-120x60.jpg', $result);

        //test ratio keeping
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 1500, 
                'height' => 1000, 
                'sizes' => array(array(1500, 1000), array(800, 600), array(400, 300), array(100, 50))
            )
        ));
        $result = $this->instance->getThumbnail($post, null, true);
        $this->assertEquals('http://www.example.com/file-1500x1000.jpg', $result);

        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 1500, 
                'height' => 1000, 
                'sizes' => array(array(1500, 1000), array(800, 600), array(400, 300), array(150, 100))
            )
        ));
        $result = $this->instance->getThumbnail($post, null, true);
        $this->assertEquals('http://www.example.com/file-150x100.jpg', $result);

        //all parameter active!
        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 1000, 
                'height' => 1000, 
                'sizes' => array(array(1000, 1000), array(800, 600), array(400, 300), array(100, 50))
            )
        ));
        $result = $this->instance->getThumbnail($post, array(600, 600), true);
        $this->assertEquals('http://www.example.com/file-1000x1000.jpg', $result);

        $post = $this->createTestPost(array(
            array(
                'path' => 'http://www.example.com/', 
                'fileName' => 'file', 
                'ext' => 'jpg',  
                'width' => 1000, 
                'height' => 1000, 
                'sizes' => array(array(1000, 1000), array(800, 600), array(400, 300), array(100, 100))
            )
        ));
        $result = $this->instance->getThumbnail($post, array(400, 400), true);
        $this->assertEquals('http://www.example.com/file-100x100.jpg', $result);

        //attachment input
        $post = new Post();
        $post->setTitle(rand());
        $post->setContent(rand());
        $this->em->persist($post);

        // standard input
        $attach = $this->createTestAttachment('http://www.example.com/', 'file', 'jpg', $post, 640, 480, 
            array(array(640, 480), array(480, 320), array(240, 160), array(80, 60)));
        $result = $this->instance->getThumbnail($attach);
        $this->assertEquals('http://www.example.com/file-240x160.jpg', $result);

        // non-image
        $attach = $this->createTestAttachment('http://www.example.com/', 'file', 'txt', $post, 640, 480, 
            array(array(640, 480), array(480, 320), array(240, 160), array(80, 60)), false, 'text/plain');
        $result = $this->instance->getThumbnail($attach);
        $this->assertNull($result);

    }


}