<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Attributes_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form(
                        array(
                            'id' => 'edit_form',
                            'action' => $this->getUrl('*/*/save', array('attribute_id' => $this->getRequest()->getParam('attribute_id'))),
                            'method' => 'post',
                        )
        );
        $model = Mage::getModel('datafeedmanager/attributes');
        $model->load($this->getRequest()->getParam('id'));
        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset('datafeedmanager_form', array('legend' => $this->__('Attribute configuration')));


        if ($this->getRequest()->getParam('id')) {
            $fieldset->addField('attribute_id', 'hidden', array(
                'name' => 'attribute_id',
            ));
        }

        $fieldset->addField('attribute_name', 'text', array(
            'name' => 'attribute_name',
            'required' => true,
            'value' => $model->getAttributeName(),
            'label' => Mage::helper('datafeedmanager')->__('Attribute code'),
            'class' => 'validate-code',
            'note' => "Use only letters (a-z), numbers (0-9) or underscore(_) in this field"
        ));
        $fieldset->addField('attribute_script', 'textarea', array(
            'name' => 'attribute_script',
            'class' => 'CodeMirror',
            'required' => true,
            'value' => $model->getAttributeScript(),
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