<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Options_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form(
                        array(
                            'id' => 'edit_form',
                            'action' => $this->getUrl('*/*/save', array('option_id' => $this->getRequest()->getParam('option_id'))),
                            'method' => 'post',
                        )
        );
        $model = Mage::getModel('datafeedmanager/options');
        $model->load($this->getRequest()->getParam('id'));
        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset('datafeedmanager_form', array('legend' => $this->__('Option configuration')));


        if ($this->getRequest()->getParam('id')) {
            $fieldset->addField('option_id', 'hidden', array(
                'name' => 'option_id',
            ));
        }

        $fieldset->addField('option_name', 'text', array(
            'name' => 'option_name',
            'required' => true,
            'value' => $model->getOptionName(),
            'label' => Mage::helper('datafeedmanager')->__('Option code'),
            'class' => 'validate-code',
            'note' => "Use only letters (a-z), numbers (0-9) or underscore(_) in this field"
        ));
         $fieldset->addField('option_param', 'text', array(
            'name' => 'option_param',
            'required' => true,
            'value' => $model->getOptionParam(),
            'label' => Mage::helper('datafeedmanager')->__('Number of additional parameters'),
            'class' => 'validate-number',
            'note' => "Use only numbers (0-9)in this field"
        ));
        $fieldset->addField('option_script', 'textarea', array(
            'name' => 'option_script',
            'class' => 'CodeMirror',
            'required' => true,
            'value' => $model->getOptionScript(),
            'label' => Mage::helper('datafeedmanager')->__('Custom php script'),
            'note' => "Create your custom php script (no openning or closing tags needed)"
        ));



        $fieldset->addField('continue', 'hidden', array(
            'name' => 'continue',
            'value' => ''
        ));

        if (Mage::getSingleton('adminhtml/session')->getDatafeedmanagerData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getDatafeedmanagerData());
            Mage::getSingleton('adminhtml/session')->setDatafeedmanagerData(null);
        } elseif (Mage::registry('datafeedmanager_data')) {
            $form->setValues(Mage::registry('datafeedmanager_data')->getData());
        }


        return parent::_prepareForm();
    }

}

?>