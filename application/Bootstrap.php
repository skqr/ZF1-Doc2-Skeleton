<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * 
     */
    protected function _initRestApi()
    {
        $frontController = Zend_Controller_Front::getInstance();

        // add the REST route for the API module only
        $restRoute = new Zend_Rest_Route($frontController, array(), array('api'));
        $frontController->getRouter()->addRoute('rest', $restRoute);
    }

    /**
     * 
     */
    protected function _initComposerAutoloader()
    {
        $autoloader = \Zend_Loader_Autoloader::getInstance();
        $composerAutoloader = require_once APPLICATION_PATH. '/../vendor/autoload.php';
        $autoloader->pushAutoloader(array($composerAutoloader, 'loadClass'), 'Symfony');
        $autoloader->pushAutoloader(array($composerAutoloader, 'loadClass'), 'DoctrineExtensions');
        $autoloader->pushAutoloader(array($composerAutoloader, 'loadClass'), 'Doctrine');
    }
}

