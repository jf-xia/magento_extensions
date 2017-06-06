<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Configurations_Edit_Tab_Cron extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
       
        $form = new Varien_Data_Form();
        $model = Mage::getModel('datafeedmanager/configurations');

        $model->load($this->getRequest()->getParam('id'));

        $this->setForm($form);
        $fieldset = $form->addFieldset('datafeedmanager_form', array('legend' => $this->__('Cron')));


        $this->setTemplate('datafeedmanager/cron.phtml');


        if (Mage::registry('datafeedmanager_data'))
            $form->setValues(Mage::registry('datafeedmanager_data')->getData());

        return parent::_prepareForm();
    }

}