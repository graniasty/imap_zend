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
        $this->_redirect('logged/transfer');
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
                    $this->view->form_display = "none";
                    $this->view->button_disabled = "disabled";

                    $direction = 'source';
                    $host1 = $formData['host1'];
                    $user1 = $formData['user1'];
                    $password1 = $formData['password1'];
                    $server = array($direction, $host1, $user1, $password1, 1);

                    $sess = Zend_Registry::get('session');
                    $t_servers = $sess->t_servers;
                    $t_servers[1] = $server;
                    $sess->t_servers = $t_servers;

                    $this->view->t_servers = $sess->t_servers;

                    if (strlen($t_servers[0][1]) > 0) {
                        $this->view->form2_display = "none";
                        $this->view->button_disabled = "";
                    }
                    echo 'tablica';
                    var_dump($sess->t_servers);
                } else {
                    $form->populate($formData);
                }
            } else {
                if ($form2->isValid($formData)) {
                    $this->view->form2_display = "none";
                    $this->view->table_display = "block";
                    $this->view->button_disabled = "disabled";

                    $host2 = $formData['host2'];
                    $user2 = $formData['user2'];
                    $password2 = $formData['password2'];

                    $direction = 'target';
                    $host1 = $formData['host2'];
                    $user1 = $formData['user2'];
                    $password1 = $formData['password2'];
                    $server = array($direction, $host2, $user2, $password2, 0);

                    $sess = Zend_Registry::get('session');
                    $t_servers = $sess->t_servers;
                    $t_servers[0] = $server;
                    $sess->t_servers = $t_servers;

                    if (strlen($t_servers[1][1] > 0)) {
                        $this->view->form_display = "none";
                        $this->view->button_disabled = "";
                    }

                    $this->view->t_servers = $sess->t_servers;
                    echo 'tablica';
                    var_dump($sess->t_servers);
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

    public function addAction() {

        $form = new Application_Form_Source();
        $this->view->form = $form;
        $sess = Zend_Registry::get('session');
        $this->view->t_servers = $sess->t_servers;

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {

                $direction = 'source';
                $host1 = $formData['host1'];
                $user1 = $formData['user1'];
                $password1 = $formData['password1'];
                $server = array($direction, $host1, $user1, $password1, 1);

                $sess = Zend_Registry::get('session');
                $t_servers = $sess->t_servers;
                $t_servers[] = $server;
                $sess->t_servers = $t_servers;
                $this->view->t_servers = $sess->t_servers;
            } else {
                $form->populate($formData);
            }
        }
    }

}
