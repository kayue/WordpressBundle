<?php

namespace Hypebeast\WordpressBundle\Tests\Security\User;

use Hypebeast\WordpressBundle\Security\User\WordpressUser;

require_once __DIR__ . '/../../WpUserMock.php';

/**
 * Test class for WordpressUser.
 * 
 * @covers Hypebeast\WordpressBundle\Security\User\WordpressUser
 */
class WordpressUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var User
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new WordpressUser;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    public function testInstantiateWithAWpUserCopiesProperties()
    {
        $wpUser = new \WP_User;
        $wpUser->expectedPropertyOne = 'expected value one';
        $wpUser->expectedPropertyTwo = 'expected value two';

        $user = new WordpressUser($wpUser);
        $this->assertEquals($user->expectedPropertyOne, $wpUser->expectedPropertyOne);
        $this->assertEquals($user->expectedPropertyTwo, $wpUser->expectedPropertyTwo);
    }

    public function testGetRolesReturnsTranslatedRoles()
    {
        $this->object->roles = $roles = array('subscriber', 'editor', 'somerole');

        $this->assertEquals(
                array_map(function($role) { return 'ROLE_WP_' . strtoupper($role); }, $roles),
                $this->object->getRoles()
        );
    }

    public function testGetPasswordReturnsPassword()
    {
        $this->object->user_pass = $expectedPassword = 'awesome password';
        $this->assertEquals($expectedPassword, $this->object->getPassword());
    }

    public function testGetSaltReturnsNull()
    {
        $this->assertNull($this->object->getSalt());
    }

    public function testGetUsernameReturnsUsername()
    {
        $this->object->user_login = $expectedUsername = 'bobluser';
        $this->assertEquals($expectedUsername, $this->object->getUsername());
    }

    public function testEqualsReturnsTrueIfUsersAreIdentical()
    {
        $comparison = new WordpressUser;
        $comparison->ID = 42;
        $this->object->ID = 42;
        $comparison->user_login = 'mickey mouse';
        $this->object->user_login = 'mickey mouse';
        $comparison->user_pass = 'minnie4eva';
        $this->object->user_pass = 'minnie4eva';

        $this->assertTrue($this->object->equals($comparison));
    }

    public function testEqualsReturnsFalseIfIdsAreDifferent()
    {
        $comparison = new WordpressUser;
        $comparison->ID = 41;
        $this->object->ID = 42;
        $comparison->user_login = 'mickey mouse';
        $this->object->user_login = 'mickey mouse';
        $comparison->user_pass = 'minnie4eva';
        $this->object->user_pass = 'minnie4eva';

        $this->assertFalse($this->object->equals($comparison));
    }

    public function testEqualsReturnsFalseIfUsernamesAreDifferent()
    {
        $comparison = new WordpressUser;
        $comparison->ID = 42;
        $this->object->ID = 42;
        $comparison->user_login = 'mickey mouse';
        $this->object->user_login = 'minnie mouse';
        $comparison->user_pass = 'minnie4eva';
        $this->object->user_pass = 'minnie4eva';

        $this->assertFalse($this->object->equals($comparison));
    }

    public function testEqualsReturnsFalseIfPasswordsAreDifferent()
    {
        $comparison = new WordpressUser;
        $comparison->ID = 42;
        $this->object->ID = 42;
        $comparison->user_login = 'mickey mouse';
        $this->object->user_login = 'mickey mouse';
        $comparison->user_pass = 'pass one';
        $this->object->user_pass = 'pass two';

        $this->assertFalse($this->object->equals($comparison));
    }

    public function testEqualsReturnsFalseIfUserIsWrongClass()
    {
        $comparison = $this->getMock('Symfony\\Component\\Security\\Core\\User\\UserInterface');
        $comparison->ID = 42;
        $this->object->ID = 42;
        $comparison->user_login = 'mickey mouse';
        $this->object->user_login = 'mickey mouse';
        $comparison->user_pass = 'minnie4eva';
        $this->object->user_pass = 'minnie4eva';

        $this->assertFalse($this->object->equals($comparison));
    }

}