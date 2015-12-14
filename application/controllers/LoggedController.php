<?php

Zend_Session::start();

if (!Zend_Registry::isRegistered('session')) {
    $session = new Zend_Session_Namespace('transfer');
    Zend_Registry::set('session', $session);
}

class LoggedController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('logged_layout');
    }

    public function indexAction() {
        $this->view->menu_display = "none";
        //cos tam
    }

   public function logoutAction() {
       $storage = new Zend_Auth_Storage_Session();
       $storage->clear();
       $this->_redirect('auth/index');
   }
   
   public function transferAction() {
       $this->view->button_disabled = "disabled";
        $sess = Zend_Registry::get('session');
        $form = new Application_Form_Source();
        $this->view->form = $form;
        $form2 = new Application_Form_Target();
        $this->view->form2 = $form2;
        $this->view->table_display = "none";
        

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();

            if ($formData['action'] == 'source') {
                if ($form->isValid($formData)) {
                    $this->view->table_display = "block";
                    $this->view->button_disabled = "disabled";

                    $host1 = $formData['host1'];
                    $user1 = $formData['user1'];
                    $password1 = $formData['password1'];

                    
                } else {
                    $form->populate($formData);
                }
            } else {
                if ($form2->isValid($formData)) {

                    $this->view->table_display = "block";
                    $this->view->button_disabled = "disabled";

                    $host2 = $formData['host2'];
                    $user2 = $formData['user2'];
                    $password2 = $formData['password2'];

                    $sess = Zend_Registry::get('session');
                    $sess->host2 = $host2;
                    $sess->user2 = $user2;
                    $sess->password2 = $password2;


                    $this->view->host1 = $sess->host1;
                    $this->view->user1 = $sess->user1;
                    $this->view->password1 = $sess->password1;

                    $this->view->host2 = $host2;
                    $this->view->user2 = $user2;
                    $this->view->password2 = $password2;
                } else {
                    $form2->populate($formData);
                }
            }
            if ((strlen($sess->host1) > 0) and ( strlen($sess->user1) > 0) and ( strlen($sess->password1) > 0) and ( strlen($sess->host2) > 0)
                    and ( strlen($sess->user2) > 0) and ( strlen($sess->password2) > 0)) {
                $this->view->button_disabled = "";
            }
        }
   }
}
