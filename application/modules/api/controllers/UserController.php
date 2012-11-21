<?php

class Api_UserController extends Zend_Rest_Controller
{
    /**
     *
     * @var My\Repository\UserRepository
     */
    private $userRepository;

    public function init()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $em = Zend_Registry::get('doctrine')->getEntityManager();
        $this->userRepository = $em->getRepository('My\Entity\User');
    }

    /**
     * Users list
     * 
     * Use parameter l for limit and o for offset
     */
    public function indexAction()
    {
        $limit = $this->_getParam('l', 20);
        $offset = $this->_getParam('o', 0);
        $users = $this->userRepository->getUsers($limit, $offset);
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
        $user = $this->userRepository->getUser($id);
        if (!empty($user)) {
            $json = Zend_Json::encode($user);
            $this->getResponse()->setBody($json);
            $this->getResponse()->setHttpResponseCode(200);
        } else {
            $this->getResponse()->setHttpResponseCode(404); // No results for the given ID.
        }
    }
     
    public function postAction()
    {
        $rawBody = $this->getRequest()->getRawBody();
        $popo = Zend_Json::decode($rawBody);
        
        $user = $this->userRepository->create($popo);
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
        
        $user = $this->userRepository->getUser($id);
        if (!empty($user)) {

            $rawBody = $this->getRequest()->getRawBody();
            $popo = Zend_Json::decode($rawBody);
            $user = $this->userRepository->update($popo, $user);

            $json = Zend_Json::encode($user);
            $this->getResponse()->setBody($json);
            $this->getResponse()->setHttpResponseCode(201);
        } else {
            $this->getResponse()->setHttpResponseCode(404); // No results for the given ID.
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
