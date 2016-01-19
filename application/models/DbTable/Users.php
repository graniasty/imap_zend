<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract {

    protected $_name = 'users';
    
/**
 * adding new User to table user, based on given login password  
 * 
 * @param type $user (string)
 * @param type $password (string)
 *  * @return type integer(succes), string(failure)
 */
    public function addUser($user, $password) {
        $salt = sha1(uniqid());
        $password = sha1($password.$salt);
        $data = array(
            'username' => $user,
            'password' => $password,
            'salt' => $salt,
            'role' => "client",
            'date_created' => time()
        );
        try {
            $this->insert($data);
        } catch (Exception $ex) {
            echo 'Nie można dodać usługi z powodu: ' . $ex->getMessage();
        }
        return $this->getAdapter()->lastInsertId();
    }

   
}
