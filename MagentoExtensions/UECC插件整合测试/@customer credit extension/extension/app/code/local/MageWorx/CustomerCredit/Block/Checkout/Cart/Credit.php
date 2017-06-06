<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_CustomerCredit_Block_Checkout_Cart_Credit extends Mage_Core_Block_Template {

	private $_credit = null;
	private $_helper = null;

	public function __construct(){

		$customerId = Mage::getSingleton('customer/session')->getCustomerId();
		$websiteId = Mage::app()->getStore()->getWebsiteId();
		$this->_credit = Mage::getModel('customercredit/credit')
							->setCustomerId($customerId)
							->setWebsiteId($websiteId)
							->loadCredit();

		$this->_helper = Mage::helper('customercredit');

	}

	public function isPartialPaymentEnabled(){
		return Mage::getStoreConfigFlag('mageworx_customers/customercredit_credit/enable_partial_credit_payment');
	}

	public function getCreditValue(){
		return $this->_credit->getValue();
	}

	public function getUseInternalCredit(){

		$session = Mage::getModel('checkout/session');
		return $session->getUseInternalCredit();

	}

}