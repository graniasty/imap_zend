<?php

Zend_Session::start();

if (!Zend_Registry::isRegistered('session')) {
    $session = new Zend_Session_Namespace('transfer');
    Zend_Registry::set('session', $session);
}

class ServerController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('param_layout');
    }

    public function preDispatch() {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector('index', 'auth');
        }
        $this->view->kto = $auth->getIdentity();
    }

    public function indexAction() {
    }

    /**
     * list servers for user
     */
    public function serversAction() {
        $id_user = Zend_Auth::getInstance()->getIdentity()->id;
        $servers = new Application_Model_DbTable_Servers();
        $servers = $servers->getUserServer($id_user);
        $this->view->servers = $servers;
    }

    /**
     * adding to current basket (multi transfer parameters) item from server list
     */
    public function addtotransferAction() {

        $form = new Application_Form_AddToTransfer();
        $this->view->form = $form;
        $sess = Zend_Registry::get('session');
        $sources = $sess->sources;
        $target = $sess->target;
        $id_server = $this->_getParam('edit');
        $server = new Application_Model_DbTable_Servers();

        $server = $server->getServerData($id_server);
        $data = array(
            'host1' => $server[0]['website'],
            'user1' => $server[0]['user']
        );
        $form->populate($data);

        $this->view->sources = $sources;
        $this->view->target = $target;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $sess = Zend_Registry::get('session');
                if ($formData['action'] == '2') {
                    $target = $sess->target;
                    $target = array('target', $formData['host1'], $formData['user1'], $formData['password1']);
                    $sess->target = $target;
                    $this->_redirect('multi/param');
                } elseif ($formData['action'] == '1') {
                    $sources = $sess->sources;
                    echo 'z sesji<br/>';
                    var_dump($sources);
                    $source = array('source', $formData['host1'], $formData['user1'], $formData['password1']);
                    $sources[] = $source;
                    $sess->sources = $sources;
                    $this->_redirect('multi/param');
                }
            } else {
                $form->populate($formData);
            }
        }
    }
    
    public function deleteAction(){
        $del = $this->_getParam('edit');
        $server = new Application_Model_DbTable_Servers();
        $server->deleteServer($del);
        $this->_redirect('server/servers');
    }

}
