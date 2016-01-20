<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRequest(){
        $FlashMessenger = new Zend_Controller_Action_Helper_FlashMessenger();
        //Zend_Controller_Action_HelperBroker::addHelper('FlashMessenger');
    }

}

