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
        $this->_redirect('auth/index');
    }

// 
    public function cleanAction() {
        $sess = Zend_Registry::get('session');
        $sess->transfers = array();
        $this->_redirect('multi/setparam');
    }

    public function transferAction() {

        $sess = Zend_Registry::get('session');
        $transfers = $sess->transfers;

        //dane testowe
//        $transfers = array(array(
//                'mail.ap.webion.pl',
//                'darek@ap.webion.pl',
//                'ulxbA2Uo',
//                'mail.wowo.webion.pl',
//                'wowo2@wowo.webion.pl',
//                'XEOPJIM2uc'
//            ),
//            array(
//                'www.olpha.com',
//                'ola@olpha.com',
//                "xxxyyy",
//                'mail.wowo.webion.pl',
//                'wowo2@wowo.webion.pl',
//                'XEOPJIM2uc'
//        ));

        $id_user = Zend_Auth::getInstance()->getIdentity()->id;

        foreach ($transfers as $one) {
            $host1 = $one[0];
            $user1 = $one[1];
            $password1 = $one[2];
            $host2 = $one[3];
            $user2 = $one[4];
            $password2 = $one[5];


            $today = time();
            $file = "out_" . uniqid();
            $status = "in progress";

            $cmd = "imapsync --host1 $host1 --user1 $user1 --password1 $password1 --host2 $host2 --user2 $user2 --password2 $password2 > /var/www/html/temp_files/$file.txt & ";
            exec($cmd);
            sleep(3);
            $fp = fopen("/var/www/html/temp_files/$file.txt", "r");
            $tekst = fread($fp, 10000);
            $pocz = strpos($tekst, "PID is");
            $pid = substr($tekst, ($pocz + 7), 5);
            $this->view->process_number = $pid;

            $transfer = new Application_Model_DbTable_Transfers();
            $transfer->addTransfer($id_user, $host1, $user1, $host2, $user2, $today, $file, $status, $pid);
        }
        $sess = Zend_Registry::get('session');
        $sess->transfers = array();
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
            $fp = fopen("/var/www/html/temp_files/$file.txt", "r");
            $size = filesize("/var/www/html/temp_files/$file.txt");
            $tekst = fread($fp, $size);
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
            unlink("/var/www/html/temp_files/$file.txt");
            return $message;
        }
    }

    private function show_history($id) {
        $transfers = new Application_Model_DbTable_Transfers();
        $transfers = $transfers->getTransferID($id);
        $history = array();
        foreach ($transfers as $key => $transfer) {
            $pid = $transfer['pid'];
            $id_tr = $transfer['id_transfers'];
            $unix = $transfer['date'];
            $transfer_data = gmdate("d-m-Y", $unix);
            $transfer = array(
                'data' => $transfer_data,
                'source_name' => $transfer['source_host'],
                'source_user' => $transfer['source_user'],
                'target_name' => $transfer['target_host'],
                'target_user' => $transfer['target_user'],
                'result' => $transfer['result'],
                'pid' => $pid,
                'id_transfers' => $id_tr
            );
            $history[] = $transfer;
        }
        return $history;
    }

    /**
     * for testing only
     */
//    public function histAction() {
//        $form = new Application_Form_Param();
//        $size = filesize("/var/www/html/temp_files/out_56a7692be96e2.txt");
//        echo 'to jest filesize';
//        var_dump($size);
//        $this->view->form = $form;
//    }

    /**
     * destroys Auth data, destroys Session data, redirect to home page
     */
    public function logoutAction() {
        $sess = Zend_Registry::get('session');
        $sess->transfers = array();
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('auth/index');
    }

    /**
     * sets transfer data to zend session and shows what was inside this var
     */
    public function setparamAction() {
        $form = new Application_Form_Param();
        $this->view->form = $form;
        $sess = Zend_Registry::get('session');
        $this->view->table_display = 'none';
        $sess = Zend_Registry::get('session');

        if (count($sess->transfers) > 0) {
            $this->view->table_display = 'block';
            $this->view->transfers = $sess->transfers;
        }
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $transfers = $sess->transfers;
                $new_transfer = array($formData['host1'], $formData['user1'], $formData['password1'], $formData['host2'], $formData['user2'], $formData['password2']);
                $transfers[] = $new_transfer;
                $sess->transfers = $transfers;
                $form->reset();
                $this->view->transfers = $sess->transfers;
                $this->view->table_display = 'block';
            } else {
                $form->populate($formData);
            }
        }
    }

    /**
     * Edition of tranfer session data
     */
    public function editAction() {
        $sess = Zend_Registry::get('session');
        $edit = $this->_getParam('edit');
        $form = new Application_Form_Param();
        $this->view->form = $form;
        $transfers = $sess->transfers;
        $edited = $transfers[$edit];
        $edited = array(
            'host1' => $edited[0],
            'user1' => $edited[1],
            'password1' => $edited[2],
            'host2' => $edited[3],
            'user2' => $edited[4],
            'password2' => $edited[5]
        );
        $form->populate($edited);

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $transfers[$edit] = array($formData['host1'], $formData['user1'], $formData['password1'], $formData['host2'], $formData['user2'], $formData['password2']);
                $sess->transfers = $transfers;
                $this->redirect('multi/setparam');
            } else {
                $form->populate($formData);
            }
        }
        $this->view->transfers = $transfers;
    }

    /**
     * removes from session certain transfer parameters
     */
    public function deleteAction() {

        $sess = Zend_Registry::get('session');

        $id = $this->_getParam('id');
        $transfers = $sess->transfers;
        unset($transfers[$id]);
        $this->view->transfers = $transfers;
        $sess->transfers = $transfers;
        $this->redirect('multi/setparam');
    }

    /**
     * removes from history set of transfer data
     */
    public function delhistoryAction() {
        $edit = $this->_getParam('edit');
        $transfer = new Application_Model_DbTable_Transfers();
        $transfer->deltransfer($edit);
        echo 'deltransfercontroller speaking';
        $this->redirect('multi/history');
    }

    /**
     * passes transfer data from history to current form and transfet session data edition
     */
    public function addtoparamAction() {
        $edit = $this->_getParam('edit');
        $transfer = new Application_Model_DbTable_Transfers();
        $result = $transfer->addTransfertoParam($edit);
        $result = $result[0];
        $data = array('host1' => $result['source_host'], 'user1' => $result['source_user'], 'host2' => $result['target_host'], 'user2' => $result['target_user']);
        $form = new Application_Form_Param();
        $this->view->form = $form;
        $form->populate($data);
        $sess = Zend_Registry::get('session');
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $add_transfers = array($formData['host1'], $formData['user1'], $formData['password1'], $formData['host2'], $formData['user2'], $formData['password2']);
                $transfers = $sess->transfers;
                $transfers[] = $add_transfers;
                $sess->transfers = $transfers;
            } else {
                $form->populate($formData);
            }
        }
        $this->view->transfers = $sess->transfers;
    }

}
