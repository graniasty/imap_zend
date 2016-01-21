<?php

class Application_Form_Param extends Zend_Form {

    public function init() {
        $this->setName('param');
        $this->setAttrib('class', 'form-inline');

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
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'col-md-4')),      
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'row form-inline', 'openOnly' => true))
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
            'Label',
            array(array('data' => 'HtmlTag'), array('tag' => 'div','class' => 'col-md-4' )),
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
            'Label',
            array(array('data' => 'HtmlTag'), array('tag' => 'div','class' => 'col-md-4')),  
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'closeOnly' => 'true'))
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
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'col-md-4')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'row', 'openOnly' => true))
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
            'Label',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'col-md-4')),
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
            'Label',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'col-md-4')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'closeOnly' => 'true'))
        ));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit');
        $submit->setAttrib('class', 'btn btn-default');
        $submit->setAttrib('value', 'transfer_source');

        $submit->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'col-md-4 col-md-offset-4 text-center'))
        ));

        $this->addElements(array(
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
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-inline')),
            'Form'
        ));
    }

}
