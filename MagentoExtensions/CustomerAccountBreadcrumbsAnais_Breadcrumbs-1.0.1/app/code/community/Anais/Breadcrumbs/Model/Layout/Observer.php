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
class Anais_Breadcrumbs_Model_Layout_Observer{
	const XML_PATH_ENABLE_ALL 		= 'breadcrumbs/settings/enable_all';
	const XML_PATH_ENABLE_SPECIFIC 	= 'breadcrumbs/settings/enable_specific';
	const XML_PATH_ENABLE_HOME 	= 'breadcrumbs/settings/enable_home';
	protected $_layout = null;
	protected $_breadcrumbsBlock = null;
	/**
	 * get the page layout instance
	 * @access protected
	 * @return Mage_Core_Model_Layout
	 * @author marius.strajeru
	 */
	protected function _getLayout(){
		if (is_null($this->_layout)){
			$this->_layout = Mage::getSingleton('core/layout');
		}
		return $this->_layout;
	}
	/**
	 * get the breadcrumb block instance
	 * @access protected
	 * @return mixed (Mage_Page_Block_Html_Breadcrumbs|null)
	 * @author marius.strajeru
	 */
	protected function _getBreadcrumbsBlock(){
		if (is_null($this->_breadcrumbsBlock)){
			$this->_breadcrumbsBlock = $this->_getLayout()->getBlock('breadcrumbs');
		}
		return $this->_breadcrumbsBlock;
	}
	/**
	 * add a breadcrumb
	 * @access protected 
	 * @param string $index
	 * @param string $label
	 * @param string $link - if empty the crumb will not have a link
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	protected function _addCrumb($index, $label, $link = ''){
		if ($breadcrumbsBlock = $this->_getBreadcrumbsBlock()){
			$breadcrumbsBlock->addCrumb($index, array(
								'label'=>$label, 
								'link'=>$link,
								)
					);
		}
		return $this;
	}
	/**
	 * adds home breadcrumb
	 * @access protected
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author marius.strajeru
	 */
	protected function _addHomeBreadcrumb(){
		if (Mage::getStoreConfigFlag(self::XML_PATH_ENABLE_HOME)){
			$this->_addCrumb('home', Mage::helper('catalog')->__('Home'), Mage::getUrl(''));
		}
		return $this;
	}
	/**
	 * adds 'My Account' breadcrumb
	 * @access protected
	 * @param bool $withLink
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author marius.strajeru
	 */
	protected function _addAccountBreadcrumb($withLink = true){
		$link = '';
		if ($withLink){
			$link = Mage::getUrl('customer/account/index');
		}
		$this->_addCrumb('account', Mage::helper('customer')->__('My Account'), $link);
		return $this;
	}
	/**
	 * adds 'My Orders' breadcrumb
	 * @access protected
	 * @param bool $withLink
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author marius.strajeru
	 */
	protected function _addOrderHistoryBreadcrumb($withLink = true){
		$link = '';
		if ($withLink){
			$link = Mage::getUrl('sales/order/history');
		}
		$this->_addCrumb('order_history', Mage::helper('sales')->__('My Orders'), $link);
		return $this;
	}
	/**
	 * adds 'order number' breadcrumb
	 * @access protected
	 * @param bool $withLink
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author marius.strajeru
	 */
	protected function _addOrderBreadcrumb($withLink = true){
		$link = '';
		$order = Mage::registry('current_order');
		if ($withLink){
			$link = Mage::getUrl('sales/order/view', array('order_id'=>$order->getId()));
		}
		$this->_addCrumb('order_view', Mage::helper('sales')->__('Order #%s', $order->getIncrementId()), $link);
		return $this;
	}
	/**
	 * check if breadcrumbs are shown
	 * @access protected
	 * @param string $page
	 * @return bool
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	protected function _checkShowBreadcrumbs($page){
		if (Mage::getStoreConfigFlag(self::XML_PATH_ENABLE_ALL)){
			return true;
		}
		$specific = Mage::getStoreConfig(self::XML_PATH_ENABLE_SPECIFIC);
		if (empty($specific)){
			return false;
		}
		$parts = explode(',', $specific);
		return (in_array($page, $parts));
	}
	/**
	 * call for customer_account_login
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_customer_account_login($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('customer_account_login');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb();
			$this->_addAccountBreadcrumb();
			$this->_addCrumb('login', Mage::helper('customer')->__('Login'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for forgot password
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_customer_account_forgotpassword($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('customer_account_forgotpassword');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb();
			$this->_addAccountBreadcrumb();
			$this->_addCrumb('forgot_password', Mage::helper('customer')->__('Forgot Your Password?'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for account index
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_customer_account_index($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('customer_account_index');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb();
			$this->_addAccountBreadcrumb(false);
		}
		return $this;
	}
	/**
	 * add breadcrumb for register page
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_customer_account_create($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('customer_account_register');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb();
			$this->_addAccountBreadcrumb();
			$this->_addCrumb('register', Mage::helper('customer')->__('Create an Account'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for account information
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_customer_account_edit($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('customer_account_edit');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb();
			$this->_addAccountBreadcrumb();
			$this->_addCrumb('info', Mage::helper('customer')->__('Account Information'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for address book index
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_customer_address_index($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('customer_address_index');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb();
			$this->_addAccountBreadcrumb();
			$this->_addCrumb('address_book', Mage::helper('customer')->__('Address Book'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for address add/edit
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_customer_address_form($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('customer_address_from');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb();
			$this->_addAccountBreadcrumb();
			$this->_addCrumb('address_book', Mage::helper('customer')->__('Address Book'), Mage::getUrl('customer/address/index'));
			if (Mage::app()->getRequest()->getParam('id')){
				$label = Mage::helper('customer')->__('Edit Address');
			}
			else{
				$label = Mage::helper('customer')->__('Add New Address');
			}
			$this->_addCrumb('address_edit', $label);
		}
		return $this;
	}
	/**
	 * add breadcrumb for order history
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_sales_order_history($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('sales_order_history');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb()
				->_addOrderHistoryBreadcrumb(false);
		}
		return $this;
	}
	/**
	 * add breadcrumb for order details
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_sales_order_view($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('sales_order_view');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb()
				->_addOrderHistoryBreadcrumb()
				->_addOrderBreadcrumb(false);
		}
		return $this;
	}
	/**
	 * add breadcrumb for order invoices
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_sales_order_invoice($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('sales_order_invoice');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb()
				->_addOrderHistoryBreadcrumb()
				->_addOrderBreadcrumb();
			$this->_addCrumb('invoices', Mage::helper('sales')->__('Invoices'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for order shipments
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_sales_order_shipment($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('sales_order_shipment');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb()
				->_addOrderHistoryBreadcrumb()
				->_addOrderBreadcrumb();
			$this->_addCrumb('shipments', Mage::helper('sales')->__('Shipments'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for order refunds
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_sales_order_creditmemo($observe){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('sales_order_creditmemo');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb()
				->_addOrderHistoryBreadcrumb()
				->_addOrderBreadcrumb();
			$this->_addCrumb('creditmemo', Mage::helper('sales')->__('Refunds'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for billing agreements
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_sales_billing_agreement_index($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('sales_billing_agreement');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb();
			$this->_addCrumb('billing_agreement', Mage::helper('sales')->__('Billing Agreements'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for recurring profiles
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_sales_recurring_profile_index($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('sales_recurring_profile');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb();
			$this->_addCrumb('billing_agreement', Mage::helper('sales')->__('Recurring Profiles'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for product reviews
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_review_customer_index($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('review_customer_index');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb();
			$this->_addCrumb('reviews', Mage::helper('review')->__('My Product Reviews'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for product review details
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_review_customer_view($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('review_customer_view');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb();
			$this->_addCrumb('reviews', Mage::helper('review')->__('My Product Reviews'), Mage::getUrl('review/customer/index'));
			$this->_addCrumb('review', Mage::helper('review')->__('Review'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for customer tags
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_tag_customer_index($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('tag_customer_index');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb();
			$this->_addCrumb('tags', Mage::helper('tag')->__('My tags'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for customer tag view
	 * @access public
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_tag_customer_view($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('tag_customer_view');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb();
			$this->_addCrumb('tags', Mage::helper('tag')->__('My tags'), Mage::getUrl('customer/tag/index'));
			$this->_addCrumb('tag', Mage::getModel('tag/tag')->load(Mage::registry('tagId'))->getName());
		}
		return $this;
	}
	/**
	 * add breadcrumb for wishlist
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_wishlist_index_index($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('wishlist_index_index');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb();
			$this->_addCrumb('wishist', Mage::helper('wishlist')->__('My Wishlist'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for downloadable products
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_downloadable_customer_products($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('downloadable_customer_products');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb();
			$this->_addCrumb('downloadable', Mage::helper('downloadable')->__('My Downloadable Products'));
		}
		return $this;
	}
	/**
	 * add breadcrumb for newsletter management
	 * @param Varien_Event_Observer
	 * @return Anais_Breadcrumbs_Model_Layout_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function controller_action_layout_render_before_newsletter_manage_index($observer){
		$showBreadcrumbs = $this->_checkShowBreadcrumbs('newsletter_manage_index');
		if ($showBreadcrumbs){
			$this->_addHomeBreadcrumb()
				->_addAccountBreadcrumb();
			$this->_addCrumb('newsletter', Mage::helper('newsletter')->__('Newsletter Subscription'));
		}
		return $this;
	}
}