<?php 
/**
 * Anais_Breadcrumbs extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Anais
 * @package    Anais_Breadcrumbs
 * @copyright  Copyright (c) 2011 Anais Software Services
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */ 
 /**
 * @category   Anais
 * @package    Anais_Breadcrumbs
 * @author     Marius Strajeru <marius.strajeru@anais-it.com>
 */ 
class Anais_Breadcrumbs_Model_Adminhtml_System_Config_Source_Breadcrumb_Pages{
	/**
	 * get the available pages
	 * @access public
	 * @return array()
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function toOptionArray(){
		return array(
            array('value' => 'customer_account_login', 'label'=>Mage::helper('breadcrumbs')->__('Account Login Page')),
            array('value' => 'customer_account_forgotpassword', 'label'=>Mage::helper('breadcrumbs')->__('Forgot Password Page')),
            array('value' => 'customer_account_index', 'label'=>Mage::helper('breadcrumbs')->__('Account Dashboard Page')),
            array('value' => 'customer_account_register', 'label'=>Mage::helper('breadcrumbs')->__('Register Page')),
            array('value' => 'customer_account_edit', 'label'=>Mage::helper('breadcrumbs')->__('Account Information Page')),
            array('value' => 'customer_address_index', 'label'=>Mage::helper('breadcrumbs')->__('Address Book Page')),
            array('value' => 'customer_address_from', 'label'=>Mage::helper('breadcrumbs')->__('Address Book  Page - Add/Edit Address')),
            array('value' => 'sales_order_history', 'label'=>Mage::helper('breadcrumbs')->__('Order History Page')),
            array('value' => 'sales_order_view', 'label'=>Mage::helper('breadcrumbs')->__('Order Details Page')),
            array('value' => 'sales_order_invoice', 'label'=>Mage::helper('breadcrumbs')->__('Order Invoices Page')),
            array('value' => 'sales_order_shipment', 'label'=>Mage::helper('breadcrumbs')->__('Order Shipments Page')),
            array('value' => 'sales_order_creditmemo', 'label'=>Mage::helper('breadcrumbs')->__('Order Creditmemos Page')),
            array('value' => 'sales_billing_agreement', 'label'=>Mage::helper('breadcrumbs')->__('Billing Agreements Page')),
            array('value' => 'sales_recurring_profile', 'label'=>Mage::helper('breadcrumbs')->__('Recurring Profile Page')),
            array('value' => 'review_customer_index', 'label'=>Mage::helper('breadcrumbs')->__('Customer Reviews Page')),
            array('value' => 'review_customer_view', 'label'=>Mage::helper('breadcrumbs')->__('Customer Review Details Page')),
            array('value' => 'tag_customer_index', 'label'=>Mage::helper('breadcrumbs')->__('Customer Tags Page')),
            array('value' => 'tag_customer_view', 'label'=>Mage::helper('breadcrumbs')->__('Customer Tag Details Page')),
            array('value' => 'wishlist_index_index', 'label'=>Mage::helper('breadcrumbs')->__('Wishlist Page')),
            array('value' => 'downloadable_customer_products', 'label'=>Mage::helper('breadcrumbs')->__('My Downloadable Products Page')),
            array('value' => 'newsletter_manage_index', 'label'=>Mage::helper('breadcrumbs')->__('Newsletter Subscription Page')),
        );
	}
}