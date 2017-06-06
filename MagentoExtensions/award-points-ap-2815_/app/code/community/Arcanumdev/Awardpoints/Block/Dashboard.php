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
 class Arcanumdev_Awardpoints_Block_Dashboard extends Mage_Core_Block_Template{public function __construct(){parent::__construct();$this->setTemplate('awardpoints/dashboard_points.phtml');}public function getPointsCurrent(){$customerId=Mage::getModel('customer/session')->getCustomerId();$award_model=Mage::getModel('awardpoints/stats');$store_id=Mage::app()->getStore()->getId();return $award_model->getPointsCurrent($customerId, $store_id);}public function getPointsReceived(){$customerId=Mage::getModel('customer/session')->getCustomerId();$award_model=Mage::getModel('awardpoints/stats');$store_id=Mage::app()->getStore()->getId();return $award_model->getPointsReceived($customerId, $store_id);}public function getPointsSpent(){$customerId=Mage::getModel('customer/session')->getCustomerId();$award_model=Mage::getModel('awardpoints/stats');$store_id=Mage::app()->getStore()->getId();return $award_model->getPointsSpent($customerId, $store_id);}public function getPointsWaitingValidation(){$customerId=Mage::getModel('customer/session')->getCustomerId();$award_model=Mage::getModel('awardpoints/stats');$store_id=Mage::app()->getStore()->getId();return $award_model->getPointsWaitingValidation($customerId, $store_id);}}