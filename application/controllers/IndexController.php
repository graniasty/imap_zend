<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $form = new Application_Form_firstForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
//                $name = $userid . '_' . $form->getValue('name');
//                $context = $form->getValue('context');
//                $haslo = $form->getValue('haslo1');
//                $sip = new Application_Model_DbTable_Sip();
//                $sip->addSip($name, $context, $haslo);
//                $this->_helper->redirector('pokaz');
//                echo "ola";
//                $process = new Process('ls');
//                echo $process->getPid();
                $this->transferAction();
                $this->_helper->redirector('transfer');
            } else {
                $form->populate($formData);
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
                echo $beta;
            } else {
                $form->populate($formData);
            }
        }
    }

    public function transferAction() {
//        imapsync --host1 mail.ap.webion.pl --user1 darek@ap.webion.pl
// --password1 ulxbA2Uo --host2 mail.wowo.webion.pl
// --user2 wowo2@wowo.webion.pl --password2 XEOPJIM2uc
        $host1 = "mail.ap.webion.pl";
        $user1 = "darek@ap.webion.pl";
        $password1 = "ulxbA2UoXX";
        $host2 = "mail.wowo.webion.pl";
        $user2 = "wowo2@wowo.webion.pl";
        $password2 = "XEOPJIM2uc";
        //$pidfile = '/var/www/html' . uniqid() . '.pid';

        //$cmd = "imapsync --host1 $host1 --user1 $user1 --password1 $password1 --host2 $host2 --user2 $user2 --password2 $password2  > /var/www/html/solone.txt & echo $!";
        $cmd = "nohup sleep 30 & echo $!";
        exec($cmd, $op);
        echo $op[0];



// otwarcie pliku do zapisu
        //exec('chmod 777 /var/www/html');
        //$fp = fopen("/var/www/html/plik.txt", "w");
        //$process = new Process($cmd);
        //echo $process->getPid();
// zapisanie danych
        //fputs($fp, $cmd);
// zamknięcie pliku
        //fclose($fp);
        //exec('chmod 777 /var/www/html/plik.txt');
        //$alfa = shell_exec("/var/www/html/nohup ./plik.txt &");
        //$beta = shell_exec("pgrep -P $$ imapsync");
        //echo $alfa;
        //$alfa = "krzaczek";
        //echo $alfa;
        //$this->view->pomidorek = $alfa;
    }

    private function check_pid($pid) {

        $cmd = "ps & echo $!";
        $alfa = shell_exec($cmd);
        if (strpos($alfa, $pid) !== false) {
            return "Transfer still in progress, wait";
        } else {
            return "Given transfer has been completed";
        }
    }

}
