<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $u = new My\Entity\User();
        var_dump($u);
        $u->setFirstname("John");
        $u->setLastname("Connor");
        var_dump($u);
        $em = Zend_Registry::get('doctrine')->getEntityManager();
        $em->persist($u);
        $em->flush();
        var_dump($u);
        $users = $em->createQuery("select u from My\Entity\User u")->execute();
        var_dump($users);
    }

}
