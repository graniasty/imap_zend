<?php

class Application_Form_Target extends Zend_Form {

    public function init() {
        $this->setName('transfer_target');
        $this->setAttrib('class', 'form-inline');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

        $action = new Zend_Form_Element_Hidden('action');
        $action->setValue('target');

        $host2 = new Zend_Form_Element_Text('host2');
        $host2->setAttrib('class', 'form-control')
                ->setLabel('host2')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('Hostname');

        $host2->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            'Label',
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3'))
        ));

        $user2 = new Zend_Form_Element_Text('user2');
        $user2->setLabel('user2');
        $user2->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('EmailAddress');

        $user2->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            'Label',
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3'))
        ));

        $password2 = new Zend_Form_Element_Text('password2');
        $password2->setLabel('pass2');
        $password2->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');


        $password2->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            'Label',
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3'))
        ));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');
        $submit->setAttrib('class', 'btn btn-default');
        $submit->setValue('transfer_target');

        $submit->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div',
                    'colspan' => 2, 'align' => 'center')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));

        $this->addElements(array(
            $action,
            $host2,
            $user2,
            $password2,
            $submit
        ));

        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
    }

}
