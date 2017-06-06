<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G.
 */
class Soon_AdvancedCache_Block_Adminhtml_Exception_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post'));

        $block = Mage::registry('advancedcache_exception');

        $info = $form->addFieldset('info_form', array('legend' => Mage::helper('advancedcache')->__('Exception information')));

        if ($block->getExceptionId()) {
            $info->addField('exception_id', 'hidden', array(
                'name' => 'exception_id',
            ));
        }

        $info->addField('item_id', 'select', array(
            'name' => 'item_id',
            'label' => Mage::helper('advancedcache')->__('Exception'),
            'values' => Mage::getModel('adminhtml/system_config_source_cms_page')->toOptionArray(),
        ));


        if (Mage::getSingleton('adminhtml/session')->getBlockData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlockData());
            Mage::getSingleton('adminhtml/session')->setBlockData(null);
        } elseif (Mage::registry('advancedcache_exception')) {
            $form->setValues(Mage::registry('advancedcache_exception')->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}