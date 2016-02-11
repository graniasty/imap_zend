<?php

/**
 * Description of SimplyCheckForm
 *
 * @author Darek
 */
class Application_Form_SimplyCheckForm extends Zend_Form {

    public function init() {
        $this->setName('simply_check');
        $this->setAttrib('class', 'form');

        $checked = new Zend_Form_Element_Text('checked');
        $checked->setLabel('Process Number')
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('Check transfer');
        $submit->setAttrib('class', 'btn');
        $submit->setLabel("Check transfer");

        $submit->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div',
                    'colspan' => 2, 'align' => 'center')),
            //array(array('row' => 'HtmlTag'), array('tag' => 'div', 'closeOnly' => true))
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group'))
        ));
        $this->addElements(array($checked, $submit));
    }

}
