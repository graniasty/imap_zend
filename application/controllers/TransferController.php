<?php

Zend_Session::start();

if (!Zend_Registry::isRegistered('session')) {
    $session = new Zend_Session_Namespace('transfer');
    Zend_Registry::set('session', $session);
}

class TransferController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        
    }

    public function paramAction() {
        $this->view->table_display = "none";
        $form = new Application_Form_Param();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $sess = Zend_Registry::get('session');
                $sess->host1 = $formData['host1'];
                $sess->user1 = $formData['user1'];
                $sess->password1 = $formData['password1'];
                $sess->host2 = $formData['host2'];
                $sess->user2 = $formData['user2'];
                $sess->password2 = $formData['password2'];
                $this->view->table_display = "block";

                $this->view->host1 = $sess->host1;
                $this->view->user1 = $sess->user1;
                $this->view->password1 = $sess->password1;
                $this->view->host2 = $sess->host2;
                $this->view->user2 = $sess->user2;
                $this->view->password2 = $sess->password2;
            }
            $form->populate($formData);
        }
    }

    public function checkAction() {

        $form = new Application_Form_SimplyCheckForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $pid = $formData['checked'];
                $beta = $this->check_pid($pid);
                $this->view->mess = $beta;
            } else {
                $form->populate($formData);
            }
        }
    }

    public function transferAction() {

        $sess = Zend_Registry::get('session');

        $host1 = $sess->host1;
        $user1 = $sess->user1;
        $password1 = $sess->password1;
        $host2 = $sess->host2;
        $user2 = $sess->user2;
        $password2 = $sess->password2;

//        $host1 = "mail.ap.webion.pl";
//        $user1 = "darek@ap.webion.pl";
//        $password1 = "ulxbA2Uo";
//        $host2 = "mail.wowo.webion.pl";
//        $user2 = "wowo2@wowo.webion.pl";
//        $password2 = "XEOPJIM2uc";
//        
//        unset($sess->host1);
//        unset($sess->user1);
//        unset($sess->password1);
//        unset($sess->host2);
//        unset($sess->user2);
//        unset($sess->password2);

        $cmd = "imapsync --host1 $host1 --user1 $user1 --password1 $password1 --host2 $host2 --user2 $user2 --password2 $password2 > /var/www/html/solone.txt & ";
        exec($cmd);
        sleep(5);
        $fp = fopen("/var/www/html/solone.txt", "r");
        $tekst = fread($fp, 100);
        $pocz = strpos($tekst, "PID is");
        $pid = substr($tekst, ($pocz + 7), 5);
        $this->view->process_number = $pid;
    }

    private function check_pid($pid) {

        $cmd = "ps & echo $!";
        $alfa = shell_exec($cmd);
        if (strpos($alfa, $pid) !== false) {
            return "<h4 style=\"color: red \" >Transfer still in progress, wait</h4>";
        } else {
            return "<h4 style=\"color: green\" >Transfer has been completed. For more details, you have to be logged in</h4>";
        }
    }

}
