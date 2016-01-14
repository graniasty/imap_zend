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

                    if (array_key_exists(0, $t_servers)) {
                        $this->view->form2_display = "none";
                        $this->view->button_disabled = "";
                    }
                    echo 'tablica';
                    var_dump($sess->t_servers);
                } else {
                    $form->populate($formData);
                    
                    $sess = Zend_Registry::get('session');
                    $t_servers = $sess->t_servers;
                     if (array_key_exists(0, $t_servers)) {
                        $this->view->form2_display = "none";
                        $this->view->button_disabled = "disabled";
                        $this->view->table_display = "block";
                        $this->view->t_servers = $sess->t_servers;
                    }
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

                    if (array_key_exists(1, $t_servers)) {
                        $this->view->form_display = "none";
                        $this->view->button_disabled = "";
                    }
//$length = strlen($t_servers)
                    $this->view->t_servers = $sess->t_servers;
                    echo 'tablica';
                    var_dump($sess->t_servers);
                } else {
                    $form2->populate($formData);
                }
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
            var_dump($formData);
            if ($form->isValid($formData)) {

                $direction = 'source';
                $host1 = $formData['host1'];
                $user1 = $formData['user1'];
                $password1 = $formData['password1'];


                $sess = Zend_Registry::get('session');
                $t_servers = $sess->t_servers;
                $id_param = end($t_servers);
                var_dump($id_param);

                $id_param = $id_param[4] + 1;
                if ($id_param < 2) {
                    $id_param = 2;
                }
                var_dump($id_param);
                $server = array($direction, $host1, $user1, $password1, $id_param);
                var_dump($server);
                $t_servers[] = $server;

                $sess->t_servers = $t_servers;
                $this->view->t_servers = $sess->t_servers;
                $this->view->form_display = "none";


                var_dump($t_servers);
            } else {
                $form->populate($formData);
            }
        }
    }

    public function cleanAction() {
        $sess = Zend_Registry::get('session');
        $t_servers = array();
        $sess->t_servers = $t_servers;
        $this->_redirect('logged/transfer');
    }

    public function deleteAction() {
        $sess = Zend_Registry::get('session');
        $id = $this->_getParam('id');
        

        if ($id >= 0) {
            $t_servers = $sess->t_servers;
            unset($t_servers[$id]);
            $sess->t_servers = $t_servers;
            $this->view->t_servers = $sess->t_servers;
        }
        var_dump($t_servers);
    }

    public function editAction() {
        
        $form = new Application_Form_Source();
        $this->view->form = $form;

        $sess = Zend_Registry::get('session');
        $id = $this->_getParam('id');
        $t_servers = $sess->t_servers;
        $edited_server = $t_servers[$id];
        var_dump($edited_server);
        $data = array('action' => $edited_server[0], 'host1' => $edited_server[1], 'user1' => $edited_server[2], 'password1' => $edited_server[3]);
        $form->populate($data);
    }

}
