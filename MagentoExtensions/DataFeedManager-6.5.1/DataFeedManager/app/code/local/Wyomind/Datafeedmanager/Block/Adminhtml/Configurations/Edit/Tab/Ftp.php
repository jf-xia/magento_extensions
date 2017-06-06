<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Configurations_Edit_Tab_Ftp extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $model = Mage::getModel('datafeedmanager/configurations');

        $model->load($this->getRequest()->getParam('id'));

        $this->setForm($form);
        $fieldset = $form->addFieldset('datafeedmanager_form', array('legend' => $this->__('FTP settings')));


        $fieldset->addField('ftp_enabled', 'select', array(
            'label' => Mage::helper('datafeedmanager')->__('Enable FTP upload'),
            'name' => 'ftp_enabled',
            'id' => 'ftp_enabled',
            'required' => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => $this->__('no')
                ),
                array(
                    'value' => 1,
                    'label' => $this->__('yes')
                )
            )
        ));


        $fieldset->addField('ftp_host', 'text', array(
            'label' => Mage::helper('datafeedmanager')->__('Host'),
            'name' => 'ftp_host',
            'id' => 'ftp_host',
        ));

        $fieldset->addField('ftp_login', 'text', array(
            'label' => Mage::helper('datafeedmanager')->__('Login'),
            'name' => 'ftp_login',
            'id' => 'ftp_login',
        ));
        $fieldset->addField('ftp_password', 'password', array(
            'label' => Mage::helper('datafeedmanager')->__('Password'),
            'name' => 'ftp_password',
            'id' => 'ftp_password',
        ));
        $fieldset->addField('ftp_dir', 'text', array(
            'label' => Mage::helper('datafeedmanager')->__('Destination directory'),
            'name' => 'ftp_dir',
            'id' => 'ftp_dir',
        ));
        $fieldset->addField('ftp_active', 'select', array(
            'label' => Mage::helper('datafeedmanager')->__('Use active mode'),
            'name' => 'ftp_active',
            'id' => 'ftp_active',
            'required' => true,
            'values' => array(
                array(
                    'value' => 0,
                    'label' => $this->__('no')
                ),
                array(
                    'value' => 1,
                    'label' => $this->__('yes')
                )
            )
        ));


        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                        ->addFieldMap('ftp_enabled', 'ftp_enabled')
                        ->addFieldMap('ftp_host', 'ftp_host')
                        ->addFieldMap('ftp_login', 'ftp_login')
                        ->addFieldMap('ftp_password', 'ftp_password')
                        ->addFieldMap('ftp_dir', 'ftp_dir')
                        ->addFieldMap('ftp_active', 'ftp_active')
                        ->addFieldDependence('ftp_host', 'ftp_enabled', 1)
                        ->addFieldDependence('ftp_login', 'ftp_enabled', 1)
                        ->addFieldDependence('ftp_password', 'ftp_enabled', 1)
                        ->addFieldDependence('ftp_active', 'ftp_enabled', 1)
                        ->addFieldDependence('ftp_dir', 'ftp_enabled', 1));



        //  $this->setTemplate('datafeedmanager/ftp.phtml');


        if (Mage::registry('datafeedmanager_data'))
            $form->setValues(Mage::registry('datafeedmanager_data')->getData());


        return parent::_prepareForm();
    }

}