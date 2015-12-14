<?php

/**
 * Description of SourceForm
 *
 * @author Darek
 */
class Application_Form_SourceForm extends Zend_Form {

    public function init() {

       // parent::_construct($option);

        $this->setMethod('post');

        $username = $this->CreateElement('text', 'username')
                ->setLabel('User Name');

        $username = setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td')),
            array('Label', array('tag' => 'td')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
        ));

        $password = $this->createElement('text', 'password')
                ->setLabel('Password');

        $password->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td')),
            array('Label', array('tag' => 'td')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
        ));

        $submit = $this->createElement('submit', 'submit')
                ->setLabel('Login');

        $submit->setDecorators(array(
            'Vievhelper',
            'Description',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td',
                    'colspan' => 2, 'align' => 'center')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
        ));

        $this->addElements(array(
            $username,
            $password,
            $submit
        ));

        $this->setDecorators(array(
            'FormElements',
            array(array('data' => 'HtmlTag'), array('tag' => 'table')),
            'Form'
        ));
    }

}
