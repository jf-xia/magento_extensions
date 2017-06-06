<?php
 /*
 * Arcanum Dev AwardPoints
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to arcanumdev@wafunotamago.com so we can send you a copy immediately.
 *
 * @category   Magento Sale Extension
 * @package    AwardPoints
 * @copyright  Copyright (c) 2012 Arcanum Dev. Y.K. (http://arcanumdev.wafunotamago.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Arcanumdev_Awardpoints_Model_Validator extends Mage_SalesRule_Model_Validator{public function process(Mage_Sales_Model_Quote_Item_Abstract $item){parent::process($item);try {$customer=Mage::getSingleton('customer/session');if($customer->isLoggedIn()){$customerId=Mage::getModel('customer/session')->getCustomerId();$award_model=Mage::getModel('awardpoints/stats');$auto_use=Mage::getStoreConfig('awardpoints/default/auto_use', Mage::app()->getStore()->getId());if($auto_use){$customer_points=$award_model->getPointsCurrent($customerId, Mage::app()->getStore()->getId());if($customer_points && $customer_points > Mage::helper('awardpoints/event')->getCreditPoints()){$cart_amount=Mage::getModel('awardpoints/discount')->getCartAmount();$cart_amount=Mage::helper('awardpoints/data')->processMathValue($cart_amount);$points_value=min(Mage::helper('awardpoints/data')->convertMoneyToPoints($cart_amount), (int)$customer_points);Mage::log('$cart_amount (VALIDATOR): ' .$cart_amount, null,'mylogfile.log');Mage::log('$points_value (VALIDATOR): ' .$points_value, null,'mylogfile.log');Mage::getSingleton('customer/session')->setProductChecked(0);Mage::helper('awardpoints/event')->setCreditPoints($points_value);}}Mage::getModel('awardpoints/discount')->apply($item);}} catch (Mage_Core_Exception $e) {Mage::getSingleton('checkout/session')->addError($e->getMessage());} catch (Exception $e){Mage::getSingleton('checkout/session')->addError($e);}return $this;}}