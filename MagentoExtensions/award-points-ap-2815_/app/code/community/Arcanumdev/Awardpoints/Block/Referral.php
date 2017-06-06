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
 class Arcanumdev_Awardpoints_Block_Referral extends Mage_Core_Block_Template{public function __construct(){parent::__construct();$this->setTemplate('referafriend/referral.phtml');$referred=Mage::getResourceModel('awardpoints/referral_collection')->addClientFilter(Mage::getSingleton('customer/session')->getCustomer()->getId());$this->setReferred($referred);}public function _prepareLayout(){parent::_prepareLayout();$pager=$this->getLayout()->createBlock('page/html_pager', 'awardpoints.referral')->setCollection($this->getReferred());$this->setChild('pager', $pager);$this->getReferred()->load();return $this;}public function getPagerHtml(){return $this->getChildHtml('pager');}public function getReferringUrl(){$userId=Mage::getSingleton('customer/session')->getCustomer()->getId();return $this->getUrl('awardpoints/index/goReferral')."referrer/$userId";}public function isPermanentLink(){$return_val=Mage::getStoreConfig('awardpoints/registration/referral_permanent',Mage::app()->getStore()->getId());return $return_val;}public function isAddthis(){if (Mage::getStoreConfig('awardpoints/registration/referral_addthis',Mage::app()->getStore()->getId())&& Mage::getStoreConfig('awardpoints/registration/referral_addthis_account',Mage::app()->getStore()->getId())!=""){return true;}return false;}public function getReferrerPoints(){return Mage::getStoreConfig('awardpoints/registration/referral_points', Mage::app()->getStore()->getId());}public function getFriendPoints(){return Mage::getStoreConfig('awardpoints/registration/referral_child_points',Mage::app()->getStore()->getId());}}