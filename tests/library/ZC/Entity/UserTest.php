<?php
/**
 *
 */
namespace ZC\Entity;
/**
 * Tests the functionality implemented in the class.
 * @covers User
 */
class UserTest extends \ModelTestCase
{
    /**
     * @covers User::__construct
     */
    public function testCanCreateUser()
    {
        $this->assertInstanceOf('ZC\Entity\User', new User());
    }
}