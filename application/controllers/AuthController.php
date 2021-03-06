<?php

class AuthController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $form = new Application_Form_Login();
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if ($this->_process($form->getValues())) {
//success means authenticate criteria has been met
                    $this->_helper->redirector('setparam', 'multi');
                }
            }
        }
        $this->view->form = $form;
    }

    protected function _process($values) {
//authentication and checking
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity($values['username']);
        $adapter->setCredential($values['password']);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }

    protected function _getAuthAdapter() {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('users')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('SHA1(CONCAT(?,salt))');

        return $authAdapter;
    }

    /**
     * New user registration
     */
    public function registerAction() {
        $form = new Application_Form_Register();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            var_dump($formData);
            
            if ($form->isValid($formData)) {
                $user = $formData['username'];
                $password = $formData['password'];
                $users = new Application_Model_DbTable_Users();
                $users->addUser($user, $password);
                $this->redirect("index/index");
            } else {
                $form->populate($formData);
            }
        }
    }

}
