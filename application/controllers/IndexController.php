<?php

Zend_Session::start();

if (!Zend_Registry::isRegistered('session')) {
    $session = new Zend_Session_Namespace('transfer');
    Zend_Registry::set('session', $session);
}

class IndexController extends Zend_Controller_Action {

    public function preDispatch() {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector('index', 'auth');
        }
        $this->view->kto = $auth->getIdentity();
    }

    public function indexAction() {
        $this->_helper->redirector('index', 'auth');
    }

}
