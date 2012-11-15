<?php
/**
 *
 */
namespace My\Entity;
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
        $this->assertInstanceOf('My\Entity\User', new User());
    }

    /**
     * @covers User::__call
     */
    public function testCanSetFirstNameAndLastNameAndRetrieveThem()
    {
    	// Given...
        $u = new User();
        // When...
        $u->setFirstname("John");
        $u->setLastname("Connor");
        $em = $this->doctrineContainer->getEntityManager();
        $em->persist($u);
        $em->flush(); 
        // Then...
        $users = $em->createQuery("select u from My\Entity\User u")->execute();
        $this->assertEquals(1, count($users));
        $this->assertEquals("John", $users[0]->getFirstname());
        $this->assertEquals("Connor", $users[0]->getLastname());
    }
}