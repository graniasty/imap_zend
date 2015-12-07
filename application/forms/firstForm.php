<?php


class Application_Form_firstForm extends Zend_Form
{

    public function init()
    {
    $this->setName('pretransfer');
    
    $id = new Zend_Form_Element_Hidden('id');
    $id->addFilter('Int');
    
    $host1 = new Zend_Form_Element_Text('host1');
    $host1->setLabel('host1')   
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
    
    $user1 = new Zend_Form_Element_Text('user1');
    $user1->setLabel('user1')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
    
    
    $password1 = new Zend_Form_Element_Text('password1');
    $password1->setLabel('password1')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
    
    $host2 = new Zend_Form_Element_Text('host2');
    $host2->setLabel('host2')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
    
    $user2 = new Zend_Form_Element_Text('user2');
    $user2->setLabel('user2')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
    
    $password2 = new Zend_Form_Element_Text('password2');
    $password2->setLabel('password2')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
    
    $submit= new Zend_Form_Element_Submit('Zapisz');
    //$submit->setAttrib('id', 'submitbutton');
    
    $this->addElements(array($host1, $user1, $password1, $host2, $user2, $password2, $submit));
    }
}