<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedsearch
 * @version    1.3.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Advancedsearch_Block_Adminhtml_Indexes_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $data = Mage::helper('awadvancedsearch/forms')->getFormData($this->getRequest()->getParam('id'));
        if(!is_object($data))
            $data = new Varien_Object($data);
        $form = new Varien_Data_Form(array('id' => 'edit_form',
                                           'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                           'method' => 'post'));
        $form->setUseContainer(true);
        $this->setForm($form);
        $fieldset = $form->addFieldset('general_fieldset', array('legend' => Mage::helper('awadvancedsearch')->__('General')));

        if($data->getStatus() === null)
            $data->setStatus(1);
        
        $fieldset->addField('status', 'select', array('label' => $this->__('Status'),
                                                      'title' => $this->__('Status'),
                                                      'name' => 'status',
                                                      'values' => Mage::getModel('awadvancedsearch/source_status')->toOptionArray()));
        $fieldset->addField('type', 'select', array('label' => $this->__('Type'),
                                                    'title' => $this->__('Type'),
                                                    'name' => 'type',
                                                    'values' => Mage::getModel('awadvancedsearch/source_catalogindexes_types')->toOptionArray()));
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store', 'multiselect', array(
                'name'      => 'store[]',
                'label'     => $this->__('Store View'),
                'required'  => TRUE,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(FALSE, TRUE),
            ));
        } else {
            if($data->getStore() && is_array($data->getStore())) {
                $stores = $data->getStore();
                if (isset($stores[0]) && $stores[0] != '') $stores = $stores[0];
                else $stores = 0;
                $data->setStore($stores);
            }

            $fieldset->addField('store', 'hidden', array(
                'name'      => 'store[]'
            ));
        }

        $fieldset = $form->addFieldset('attributes_fieldset', array('legend' => $this->__('Attributes')));
        $fieldset->setFieldsetContainerId('awas_attributes');
        $form->setValues($data);
        return parent::_prepareForm();
    }
}
