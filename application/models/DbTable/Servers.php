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

    /**
     * gets data of one server according to id_server
     * @param type $id integer
     * @return type multiarray
     */
    public function getServerData($id) {
        $row = $this->fetchAll("id_server = $id");
        return $row->toArray();
    }

    /**
     * gets all servers for certain user
     */
    public function getUserServer($id) {
        $row = $this->fetchAll("id_user = $id");
        return $row->toArray();
    }

    /**
     * check server in db if exists does not put it again into db
     * @param type $host2
     * @return type multiarray
     */
    public function checkServer($host2, $user2) {
        $row = $this->fetchAll("website = '$host2'and user = '$user2' ");
        return $row->toArray();
    }

    public function deleteServer($del){
           $this->delete('id_server =' . (int) $del);
    }
   
}
