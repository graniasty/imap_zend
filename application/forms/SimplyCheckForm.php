<?php

/**
 * Description of SimplyCheckForm
 *
 * @author Darek
 */

class Application_Form_SimplyCheckForm extends Zend_Form
{

    public function init()
    {
    $this->setName('simply_check');
       
    $checked = new Zend_Form_Element_Text('checked');
    $checked->setLabel('Process Number')   
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
    
    $submit= new Zend_Form_Element_Submit('GO');
    $this->addElements(array($checked, $submit));
    }
}