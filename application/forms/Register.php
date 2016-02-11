<?php

class Application_Form_Register extends Zend_Form {

    public function init() {
        $this->setName("register");
        $this->setMethod("post");
        $this->setAttrib('class', 'form-group');

        $this->addElement('text', 'username', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required' => true,
            'label' => 'Username',
            'class' => 'form-control'
        ));
        
        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required' => true,
            'label' => 'Password',
            'class' => 'form-control'
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore' => true,
            'label' => 'Register',
            'class' => 'form-control'
        ));
    }

}
