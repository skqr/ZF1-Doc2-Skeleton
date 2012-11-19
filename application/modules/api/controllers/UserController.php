<?php

class Api_UserController extends Zend_Rest_Controller
{
    /**
     * @var Doctrine\ORM\EntityManager The Doctrine entity manager instance.
     */
    private $em;

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->em = Zend_Registry::get('doctrine')->getEntityManager();
    }

    public function indexAction()
    {
        $query = "select u from My\Entity\User u";
        $users = $this->em->createQuery($query)->execute();
        $json = Zend_Json::encode($users);
        $this->getResponse()->setBody($json);
        $this->getResponse()->setHttpResponseCode(200);
    }
     
    public function getAction()
    {
        $id = $this->_getParam('id');
        if (!is_numeric($id)) {
            throw new Exception("The ID is not an integer.");
        }
        $query = "select u from My\Entity\User u where u.id = $id";
        $users = $this->em->createQuery($query)->execute();
        if (1 === count($users)) {
            $json = Zend_Json::encode($users[0]);
            $this->getResponse()->setBody($json);
            $this->getResponse()->setHttpResponseCode(200);
        } elseif (0 === count($users)) {
            $this->getResponse()->setHttpResponseCode(404); // No results for the given ID.
        } else {
            throw new Exception("Selecting by ID returned mutliple results.");
        }
    }
     
    public function postAction()
    {
        $rawBody = $this->getRequest()->getRawBody();
        $popo = Zend_Json::decode($rawBody);
        $user = new My\Entity\User();
        if (isset($popo['firstname'])) {
            $user->setFirstname($popo['firstname']);
        }
        if (isset($popo['lastname'])) {
            $user->setLastname($popo['lastname']);
        }
        $this->em->persist($user);
        $this->em->flush();
        $body = "User with ID {$user->getId()} created.\n";
        $this->getResponse()->setBody($body);
        $this->getResponse()->setHttpResponseCode(201);
    }
     
    public function putAction()
    {
        $id = $this->_getParam('id');
        if (!is_numeric($id)) {
            throw new Exception("The ID is not an integer."); // Bad request (400)?
        }
        $query = "select u from My\Entity\User u where u.id = $id";
        $users = $this->em->createQuery($query)->execute();
        if (1 === count($users)) {
            $json = Zend_Json::encode($users[0]);
            $this->getResponse()->setBody($json);
            $this->getResponse()->setHttpResponseCode(200);
            $popo = null;
            try {
                $rawBody = $this->getRequest()->getRawBody();
                $popo = Zend_Json::decode($rawBody);
            } catch (Exception $e) {
                $this->getResponse()->setBody($e->getMessage());
                $this->getResponse()->setHttpResponseCode(500);
                return;
            }
            $user = $users[0];
            if (isset($popo['firstname'])) {
                $user->setFirstname($popo['firstname']);
            }
            if (isset($popo['lastname'])) {
                $user->setLastname($popo['lastname']);
            }
            $this->em->persist($user);
            $this->em->flush();
            $body = "User with ID {$user->getId()} updated.\n";
            $this->getResponse()->setBody($body);
            $this->getResponse()->setHttpResponseCode(201);$this->getResponse()->setHttpResponseCode(201);
        } elseif (0 === count($users)) {
            $this->getResponse()->setHttpResponseCode(404); // No results for the given ID.
        } else {
            throw new Exception("Selecting by ID returned mutliple results.");
        }
    }
     
    public function deleteAction()
    {
        $id = $this->_getParam('id');
        if (!is_numeric($id)) {
            throw new Exception("The ID is not an integer.");
        }
        $users = $this->em->createQuery("select u from My\Entity\User u where u.id = $id")->execute();
        if (1 === count($users)) {
            $json = Zend_Json::encode($users[0]);
            $this->getResponse()->setBody($json);
            $user = $users[0];
            $this->em->remove($user);
            $this->em->flush();
            $this->getResponse()->setHttpResponseCode(200);
        } elseif (0 === count($users)) {
            $this->getResponse()->setHttpResponseCode(404); // No results for the given ID.
        } else {
            // Selecting by ID returned mutliple results.
            $this->getResponse()->setHttpResponseCode(500);
        }
    }
}
