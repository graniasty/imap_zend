<?php

class Application_Form_Param extends Zend_Form {

    public function init() {
        $this->setName('param');
        $this->setAttrib('class', 'form-inline');

        $action = new Zend_Form_Element_Hidden('action');
        $action->setValue('param');

        $host1 = new Zend_Form_Element_Text('host1');
        $host1->setAttrib('class', 'form-control')
                ->setLabel('host1')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('Hostname');
                
        $host1->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            'Label',
            //array('Input', array('class' => 'form-control')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3')),
                //array(array('row' => 'HtmlTag'), array('tag' => 'div','class'=>'form-inline', 'openOnly' => true))
        ));

        $user1 = new Zend_Form_Element_Text('user1');
        $user1->setLabel('user1')
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('EmailAddress');
        

        $user1->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            //array(array('data' => 'HtmlTag'), array('tag' => 'div')),
            'Label',
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3'))
        ));

        $password1 = new Zend_Form_Element_Text('password1');
        $password1->setLabel('pass1')
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $password1->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            // array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'col-md-2')),
            'Label',
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3'))
        ));

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
            //array('Input', array('class' => 'form-control')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3')),
                //array(array('row' => 'HtmlTag'), array('tag' => 'div','class'=>'form-inline', 'openOnly' => true))
        ));

        $user2 = new Zend_Form_Element_Text('user2');
        $user2->setLabel('user2')
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('EmailAddress');
        

        $user2->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            //array(array('data' => 'HtmlTag'), array('tag' => 'div')),
            'Label',
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group col-md-3'))
        ));

        $password2 = new Zend_Form_Element_Text('password2');
        $password2->setLabel('pass2')
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

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
        $submit->setAttrib('class', 'btn btn-default');
        $submit->setAttrib('value', 'transfer_source');

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
            $edit,
            $action,
            $host1,
            $user1,
            $password1,
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
