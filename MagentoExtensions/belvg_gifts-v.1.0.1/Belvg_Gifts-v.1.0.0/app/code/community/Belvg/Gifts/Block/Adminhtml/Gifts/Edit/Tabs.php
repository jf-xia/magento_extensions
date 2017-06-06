<?php

/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
 /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 *****************************************/
 /* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
 /***************************************
 *         DISCLAIMER   *
 *****************************************/
 /* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 *****************************************************
 * @category   Belvg
 * @package    Belvg_Gifts
 * @copyright  Copyright (c) 2010 - 2012 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_Gifts_Block_Adminhtml_Gifts_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct()
    {
        parent::__construct();
        $this->setId('gifts_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('gifts')->__('Rule Information'));
    }

    protected function _beforeToHtml()
    {
    	$store_switcher = $this->getLayout()->createBlock('adminhtml/store_switcher', 'store_switcher');
    	
        $this->addTab('form_info', array(
                'label' => Mage::helper('gifts')->__('Settings'),
                'title' => Mage::helper('gifts')->__('Settings'),
                'content' => $store_switcher->toHtml() . $this->getLayout()->createBlock('gifts/adminhtml_gifts_edit_tab_form')->toHtml(),
        ));


        $store_switcher = $this->getLayout()->createBlock('adminhtml/store_switcher', 'store_switcher');
        $products_grid = $this->getLayout()->createBlock('gifts/adminhtml_gifts_edit_tab_products', 'gifts_edit_tab_products');
        $grid_serializer = $this->getLayout()->createBlock('adminhtml/widget_grid_serializer');
        $grid_serializer->initSerializerBlock('gifts_edit_tab_products', 'getRelatedProducts', 'gifts_assigned_products', 'assigned_products');

        $this->addTab('form_products', array(
                'label' => Mage::helper('gifts')->__('Products'),
                'title' => Mage::helper('gifts')->__('Products'),
                'content' => $products_grid->toHtml() . $grid_serializer->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
