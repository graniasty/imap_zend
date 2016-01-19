<?php

Zend_Session::start();

if (!Zend_Registry::isRegistered('session')) {
    $session = new Zend_Session_Namespace('transfer');
    Zend_Registry::set('session', $session);
}

class MultiController extends Zend_Controller_Action {

    public $edited;

    public function init() {
        $this->_helper->layout->setLayout('param_layout');
    }

    public function preDispatch() {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity())
            $this->helper->redirector('auth', 'index');
        $this->view->user = $auth->getIdentity();
    }

    public function indexAction() {
        $this->_redirect('logged/transfer');
    }

    public function paramAction() {

        $this->view->table_display = "none";
        $form = new Application_Form_Source();
        $this->view->form = $form;
        $form2 = new Application_Form_Target();
        $this->view->form2 = $form2;
        $sess = Zend_Registry::get('session');
        $sources = $sess->sources;
        $target = $sess->target;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($formData['action'] == 'target') {
                if ($form2->isValid($formData)) {
                    $direction = 'target';
                    $host2 = $formData['host2'];
                    $user2 = $formData['user2'];
                    $password2 = $formData['password2'];
                    $server = array($direction, $host2, $user2, $password2);
                    $target = $server;
                    $sess->target = $target;
                } else {
                    $form2->populate($formData);
                }
            } else {
                if ($form->isValid($formData)) {
                    $direction = 'source';
                    $host1 = $formData['host1'];
                    $user1 = $formData['user1'];
                    $password1 = $formData['password1'];
                    $server = array($direction, $host1, $user1, $password1);
                    $sources[] = $server;
//                    if (strlen($formData['edit']) > 0) {
//                        echo "wchodzi zawsze";
//                        $edit = $formData['edit'];
//                        $sources[$edit] = $server;
//                        $flag = 1;
//                    } else {
//                        
//                        $flag = 1;
//                    }

                    $sess->sources = $sources;
                    $this->view->sources = $sources;
                    $form->reset();
                } else {
                    $form->populate($formData);
                }
            }
        }
        $this->view->target = $target;
        $this->view->sources = $sources;
        //var_dump($sources);
        if (count($target) > 0) {
            $this->view->form2_display = "none";
            $this->view->table_display = "block";
        }

        if (count($sources) > 0) {
            $this->view->table_display = "block";
        }

        if ((count($target) == 0) || (count($sources) == 0 )) {
            $this->view->button_disabled = "disabled";
        }

//        $edit = $this->_getParam('edit');
//        echo "to jest wartość edit";
//        echo $edit;
//        echo $flag;
//        if (strlen($edit) > 0) {
//            $edited = $sess->sources[$edit];
//            $edited = array('edit' => $edit, 'action' => 'source', 'host1' => $edited[1], 'user1' => $edited[2], 'password1' => $edited[3]);
//            $form->populate($edited);
//        }
//        if (isset($flag)) {
//            if ($flag == 1) {
//                $form->reset();
//                $flag = -1;
//            }
//        }
    }

    public function cleanAction() {
        $sess = Zend_Registry::get('session');
        $sess->sources = array();
        $sess->target = array();
        $this->_redirect('multi/param');
    }

    public function deleteAction() {

        $sess = Zend_Registry::get('session');
        $id = $this->_getParam('id');

        if ($id == 'target') {
            $sess->target = array();
        } else {
            $sources = $sess->sources;
            unset($sources[$id]);
            $sess->sources = $sources;
            $this->view->sources = $sess->sources;
            $this->view->target = $sess->target;
        }
        $this->_redirect('multi/param');
    }

    public function transferAction() {

        //dane testowe
//        
//        $host1 = "mail.ap.webion.pl";
//        $user1 = "darek@ap.webion.pl";
//        $password1 = "ulxbA2Uo";
//        $host2 = "mail.wowo.webion.pl";
//        $user2 = "wowo2@wowo.webion.pl";
//        $password2 = "XEOPJIM2uc";
//        

        $sess = Zend_Registry::get('session');
        $sources = $sess->sources;
        $target = $sess->target;
        
// dane testowe
//        $sources = array(array(
//                'host' => 'mail.ap.webion.pl',
//                'user' => 'darek@ap.webion.pl',
//                'password' => 'ulxbA2UoXX'),
//            array(
//                'host' => 'www.olpha.com',
//                'user' => 'ola@olpha.com',
//                'password' => "xxxyyy"
//        ));
//        $target = array(
//            'host' => 'mail.wowo.webion.pl',
//            'user' => 'wowo2@wowo.webion.pl',
//            'password' => 'XEOPJIM2uc'
//        );
        $id_user = Zend_Auth::getInstance()->getIdentity()->id;

//        $host2 = $target['host'];
//        $user2 = $target['user'];
//        $password2 = $target['password'];
        $host2 = $target[1];
        $user2 = $target[2];
        $password2 = $target[3];
        $server = new Application_Model_DbTable_Servers();
        $wynik = $server->checkServer($host2, $user2);
        if ($wynik == false) {
            $id_target = $server->addServer($id_user, "target", $host2, $user2);
        } else {
            $id_target = $wynik[0]['id_server'];
        }

        foreach ($sources as $one) {
//            $host1 = $one['host'];
//            $user1 = $one['user'];
//            $password1 = $one['password'];
            $host1 = $one[1];
            $user1 = $one[2];
            $password1 = $one[3];
            $id_source = $server->addServer($id_user, "source", $host1, $user1);

            $today = time();
            $file = "out_" . uniqid();
            $status = "in progress";

            $cmd = "imapsync --host1 $host1 --user1 $user1 --password1 $password1 --host2 $host2 --user2 $user2 --password2 $password2 > /var/www/html/temp_files/$file.txt & ";
            exec($cmd);
            sleep(5);
            $fp = fopen("/var/www/html/temp_files/$file.txt", "r");
            $tekst = fread($fp, 100);
            $pocz = strpos($tekst, "PID is");
            $pid = substr($tekst, ($pocz + 7), 5);
            $this->view->process_number = $pid;

            $transfer = new Application_Model_DbTable_Transfers();
            $transfer->addTransfer($id_user, $id_target, $id_source, $today, $file, $status, $pid);
        }
        $sess = Zend_Registry::get('session');
        //$sess->sources = array();
        //$sess->target = array();
        $this->_redirect('multi/history');
    }

    public function historyAction() {
        $id_user = Zend_Auth::getInstance()->getIdentity()->id;
        $transfers = new Application_Model_DbTable_Transfers();
        $transfers = $transfers->getTransferPID($id_user);
        foreach ($transfers as $key => $transfer) {

            $pid = $transfer['pid'];
            $id_transfer = $transfer['id_transfers'];
            $status = $this->check_pid($pid);
            $status_transfers = new Application_Model_DbTable_Transfers();
            $status_transfers->setStatus($status, $id_transfer);
        };

        $history = $this->show_history($id_user);
        $this->view->history = $history;
    }

    private function check_pid($pid) {

        $cmd = "ps & echo $!";
        $alfa = shell_exec($cmd);
        if (strpos($alfa, $pid) !== false) {
            return "in progress";
        } else {
            $transfers = new Application_Model_DbTable_Transfers();
            $file = $transfers->getFile($pid);
            $file = $file[0]['file'];
            //var_dump($file);
            $fp = fopen("/var/www/html/temp_files/$file.txt", "r");
            $tekst = fread($fp, 30000);
            if (strpos($tekst, "Failure: can not open imap connection")) {
                $message = "Error connection";
            } elseif (strpos($tekst, "Failure: error login on")) {
                $message = "Error login";
            } elseif (strpos($tekst, "Detected 0 errors")) {
                $message = "Transfer successful";
            } else {
                $message = "Finished, no details";
            }
            $transfers->setZeroPid($pid);
            //unlink("/var/www/html/temp_files/$file.txt");
            return $message;
        }
    }

    private function show_history($id) {
        $transfers = new Application_Model_DbTable_Transfers();
        $transfers = $transfers->getTransferID($id);
        $servers = new Application_Model_DbTable_Servers();

        $history = array();
        foreach ($transfers as $key => $transfer) {
            $id = $transfer['server_source'];
            $source = $servers->getServerData($id);
//            echo 'sources<br/>';
//            var_dump($source);
            $source = $source[0];
            $id = $transfer['server_target'];
            $target = $servers->getServerData($id);
//            echo 'target<br/>';
//            var_dump($target);
            $target = $target[0];
            $pid = $transfer['pid'];
            $id_tr = $transfer['id_transfers'];
            $unix = $transfer['date'];
            $transfer_data = gmdate("d-m-Y", $unix);
            $transfer = array(
                'data' => $transfer_data,
                'source_name' => $source['website'],
                'source_user' => $source['user'],
                'target_name' => $target['website'],
                'target_user' => $target['user'],
                'result' => $transfer['result'],
                'pid' => $pid,
                'id_transfers' => $id_tr
            );
            $history[] = $transfer;
        }
        return $history;
    }

    /**
     * list servers for user
     */
    public function serversAction() {
        $id_user = Zend_Auth::getInstance()->getIdentity()->id;
        $servers = new Application_Model_DbTable_Servers();
        $servers = $servers->getUserServer($id_user);
        //var_dump($servers);
        $this->view->servers = $servers;
    }

    /**
     * for testing only
     */
    public function histAction() {
        //$this->headScript()->prependFile('/js/bootstrap.min.js');
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

    /**
     * Edition of session data sources and target (basket)
     */
    public function editAction() {
        $sess = Zend_Registry::get('session');
        $edit = $this->_getParam('edit');
        $form2 = new Application_Form_Target();
        $this->view->form2 = $form2;

        if ($edit == 'target') {
            $this->view->edit_form_label = "Target mailbox parameters: Edition";
            $target = $sess->target;
            $edit_target = array('host2' => $target[1], 'user2' => $target[2], 'password2' => $target[3]);
            $form2->populate($edit_target);

            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if ($form2->isValid($formData)) {
                    $sess->target = array('target', $formData['host2'], $formData['user2'], $formData['password2']);
                    $form2->reset();
                    $this->_redirect('multi/param');
                } else {
                    $form2->populate($formData);
                }
            }
        } else {
            $edit = $this->_getParam('edit');
            $this->view->edit_form_label = "Source mailbox parameters: Edition";
            $sources = $sess->sources;
            $edit_source = $sources[$edit];
            $edit_source = array('host2' => $edit_source[1], 'user2' => $edit_source[2], 'password2' => $edit_source[3]);
            $form2->populate($edit_source);
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if ($form2->isValid($formData)) {
                    $sources[$edit] = array('source', $formData['host2'], $formData['user2'], $formData['password2']);
                    $sess->sources = $sources;
                    $form2->reset();
                    $this->_redirect('multi/param');
                } else {
                    $form2->populate($formData);
                }
            }
        }
        $this->view->target = $sess->target;
        $this->view->sources = $sess->sources;
    }

    /**
     * destroys Auth data, destroys Session data, redirect to home page
     */
    public function logoutAction() {
//        $sess = Zend_Registry::get('session');
//        $sess->sources = array();
//        $sess->target = array();
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('auth/index');
    }

}
