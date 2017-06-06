<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G. ()
 */
class Soon_AdvancedCache_Block_Adminhtml_Blocks_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post'));

        $block = Mage::registry('advancedcache_block');

        $info = $form->addFieldset('info_form', array('legend' => Mage::helper('advancedcache')->__('Block information')));

        if ($block->getBlockId()) {
            $info->addField('block_id', 'hidden', array(
                'name' => 'block_id',
            ));
        }

        $info->addField('identifier', 'text', array(
            'name' => 'identifier',
            'label' => Mage::helper('advancedcache')->__('Identifier (Tag)'),
            'title' => Mage::helper('advancedcache')->__('Identifier (Tag)'),
            'class' => 'validate-xml-identifier',
            'required' => true,
        ));

        $info->addField('block_class', 'text', array(
            'name' => 'block_class',
            'label' => Mage::helper('advancedcache')->__('Block Class'),
            'title' => Mage::helper('advancedcache')->__('Block Class'),
            'required' => true,
        ));

        $info->addField('block_name', 'text', array(
            'name' => 'block_name',
            'label' => Mage::helper('advancedcache')->__('Block Name in Layout'),
            'title' => Mage::helper('advancedcache')->__('Block Name in Layout'),
            'required' => true,
        ));

        $info->addField('description', 'textarea', array(
            'name' => 'description',
            'label' => Mage::helper('advancedcache')->__('Description'),
            'title' => Mage::helper('advancedcache')->__('Description'),
        ));
        
        $info->addField('expire', 'select', array(
            'name' => 'expire',
            'label' => Mage::helper('advancedcache')->__('Expiration'),
            'values' => Mage::getModel('advancedcache/config')->getExpireOptions(),
        ));

        $info->addField('special_configuration', 'select', array(
            'name' => 'special_configuration',
            'label' => Mage::helper('adminhtml')->__('Special Configuration'),
            'values' => array(
                array(
                    'value' => '',
                    'label' => '',
                ),
                array(
                    'value' => Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_CURRENT_CATEGORY,
                    'label' => Mage::helper('advancedcache')->__('Depends on Current Category'),
                ),
                array(
                    'value' => Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_CURRENT_PRODUCT,
                    'label' => Mage::helper('advancedcache')->__('Depends on Current Product'),
                ),
                array(
                    'value' => Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_PRODUCTS_LIST,
                    'label' => Mage::helper('advancedcache')->__('Is Product List Rewrite'),
                ),
                array(
                    'value' => Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_PRODUCT_VIEW,
                    'label' => Mage::helper('advancedcache')->__('Is Product View Rewrite'),
                ),
                array(
                    'value' => Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_BREADCRUMBS,
                    'label' => Mage::helper('advancedcache')->__('Is Breadcrumbs Rewrite'),
                ),
            ),
        ));

        $info->addField('status', 'select', array(
            'name' => 'status',
            'label' => Mage::helper('adminhtml')->__('Status'),
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('advancedcache')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('advancedcache')->__('Disabled'),
                ),
            ),
        ));

        if (Mage::getSingleton('adminhtml/session')->getBlockData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlockData());
            Mage::getSingleton('adminhtml/session')->setBlockData(null);
        } elseif (Mage::registry('advancedcache_block')) {
            $form->setValues(Mage::registry('advancedcache_block')->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}