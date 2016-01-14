<?php

class Application_Model_DbTable_Transfers extends Zend_Db_Table_Abstract {

    protected $_name = 'transfers';

    public function addTransfer($id_user, $id_target, $id_source, $date, $file, $status, $pid) {
        $data = array(
            'id_user' => $id_user,
            'server_target' => $id_target,
            'server_source' => $id_source,
            'date' => $date,
            'file' => $file,
            'result' => $status,
            'pid' => $pid
        );
        try {
            $this->insert($data);
        } catch (Exception $ex) {
            echo 'Nie można dodać usługi z powodu: ' . $ex->getMessage();
        }
        return $this->getAdapter()->lastInsertId();
    }

    public function getTransferPID($id_user) {
        $row = $this->fetchAll("id_user = $id_user and pid != 0 ");
        return $row->toArray();
    }

    public function setStatus($status, $id_transfer) {
        $data = array('result' => $status);
        try {
            $this->update($data, "id_transfers like $id_transfer");
        } catch (Exception $ex) {
            echo 'Nie można aktualizować bazy z powodu: ' . $ex->getMessage();
        }
    }

    public function setZeroPid($pid) {
        $data = array('pid' => 0);
        try {
            $this->update($data, "pid like $pid");
        } catch (Exception $ex) {
            echo 'Nie można aktualizować bazy z powodu: ' . $ex->getMessage();
        }
    }
    
    public function getTransferID($id){
        $row = $this->fetchAll("id_user = $id", "id_transfers desc");      
        return $row->toArray();
    }
    
    public function getFile($pid){
        $row = $this->fetchAll("pid = $pid");
        return $row->toArray();
    }

}
