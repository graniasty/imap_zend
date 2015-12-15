<?php

Zend_Session::start();

if (!Zend_Registry::isRegistered('session')) {
    $session = new Zend_Session_Namespace('YourSiteName');
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
//
//        $form = new Application_Form_Login;
//        $this->view->form = $form;
//        $formData = $this->getRequest()->getPost();
//        if ($form->isValid($formData)) {
//        } else {
//            $form->populate($formData);
//        }
    }

//    public function checkAction() {
//
//        $form = new Application_Form_SimplyCheckForm();
//        $this->view->form = $form;
//        if ($this->getRequest()->isPost()) {
//            $formData = $this->getRequest()->getPost();
//            if ($form->isValid($formData)) {
//                $pid = $formData['checked'];
//                $beta = $this->check_pid($pid);
//                echo $beta;
//            } else {
//                $form->populate($formData);
//            }
//        }
//    }
//
//    private function check_pid($pid) {
//
//        $cmd = "ps & echo $!";
//        $alfa = shell_exec($cmd);
//        if (strpos($alfa, $pid) !== false) {
//            return "<h4 style=\"color: red \" >Transfer still in progress, wait</h4>";
//        } else {
//            return "<h4 style=\"color: green\" >Transfer has been completed. For more details, you have to be logged in</h4>";
//        }
//    }

}
