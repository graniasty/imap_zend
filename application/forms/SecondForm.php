<?php

class Application_Form_SecondForm extends Zend_Form {

    public function init() {
        $this->setName('transfer_target');
        $this->setAttrib('class', 'form-inline');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

        $host2 = new Zend_Form_Element_Text('host2');
         $host2->setAttrib('class','form-control')
        ->setLabel('host2');

        $host2->setDecorators(array(
            'ViewHelper',
            //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'col-md-2')),
            'Label', 
            //array('Input', array('class' => 'form-control')),
            array(array('row'=>'HtmlTag'), array('tag' => 'div' , 'class' => 'form-group col-md-3')),
            //array(array('row' => 'HtmlTag'), array('tag' => 'div','class'=>'form-inline', 'openOnly' => true))
        ));

        $user2 = new Zend_Form_Element_Text('user2');
        $user2->setLabel('user2');
        $user2->setAttrib('class','form-control');

        $user2->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            //array(array('data' => 'HtmlTag'), array('tag' => 'div')),
            'Label',
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3'))
        ));

        $password2 = new Zend_Form_Element_Text('password2');
        $password2->setLabel('pass2');
        $password2->setAttrib('class', 'form-control');

        $password2->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
           // array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'col-md-2')),
            'Label',
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3'))
        ));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');
        $submit->setAttrib('class','btn btn-default');
        $submit->setValue('transfer_target');

        $submit->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div',
                    'colspan' => 2, 'align' => 'center')),
            //array(array('row' => 'HtmlTag'), array('tag' => 'div', 'closeOnly' => true))
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));

        $this->addElements(array(
            $host2,
            $user2,
            $password2,
            $submit
        ));

        $this->setDecorators(array(
            'FormElements',
            //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' =>'form-inline')),
            'Form'
        ));
    }

}
