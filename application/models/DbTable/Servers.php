<?php

class Application_Model_DbTable_Servers extends Zend_Db_Table_Abstract {

    protected $_name = 'servers';

    public function addServer($id_user, $direction, $website, $user) {
        $data = array(
            'id_user' => $id_user,
            'direction' => $direction,
            'website' => $website,
            'user' => $user
        );
        try {
            $this->insert($data);
        } catch (Exception $ex) {
            echo 'Nie można dodać usługi z powodu: ' . $ex->getMessage();
        }
        return $this->getAdapter()->lastInsertId();
    }
    
    public function getServerData($id){
        $row = $this->fetchAll("id_server = $id");
        return $row->toArray();
    }

}
