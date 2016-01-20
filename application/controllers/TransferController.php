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

                    $this->view->host1 = $host1;
                    $this->view->user1 = $user1;
                    $this->view->password1 = $password1;

                    $sess = Zend_Registry::get('session');
                    $sess->host1 = $host1;
                    $sess->user1 = $user1;
                    $sess->password1 = $password1;

                    $this->view->host2 = $sess->host2;
                    $this->view->user2 = $sess->user2;
                    $this->view->password2 = $sess->password2;
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
        
        $host1=$sess->host1;
        $user1=$sess->user1;
        $password1=$sess->password1;
        $host2=$sess->host2;
        $user2=$sess->user2;
        $password2=$sess->password2;
        
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

        //$pidfile = '/var/www/html' . uniqid() . '.pid';

        $cmd = "imapsync --host1 $host1 --user1 $user1 --password1 $password1 --host2 $host2 --user2 $user2 --password2 $password2 > /var/www/html/solone.txt & ";
        //$cmd = "nohup sleep 30 & echo $!";
        exec($cmd);
        //echo $op[1];
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
