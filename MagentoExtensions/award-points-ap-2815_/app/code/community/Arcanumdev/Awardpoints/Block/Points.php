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
 class Arcanumdev_Awardpoints_Block_Points extends Mage_Core_Block_Template{public function __construct(){parent::__construct();$this->setTemplate('referafriend/points.phtml');$points=Mage::getResourceModel('awardpoints/stats_collection')->addClientFilter(Mage::getSingleton('customer/session')->getCustomer()->getId());$this->setPoints($points);}public function _prepareLayout(){parent::_prepareLayout();$pager=$this->getLayout()->createBlock('page/html_pager','awardpoints.points')->setCollection($this->getPoints());$this->setChild('pager', $pager);$this->getPoints()->load();return $this;}public function getPagerHtml(){return $this->getChildHtml('pager');}public function getTypeOfPoint($order_id, $referral_id){$toHtml ='';if($referral_id){$referrer=Mage::getModel('customer/customer')->load($referral_id);$toHtml .='<div class="arcanum-in-title">'.$this->__('Referral points (%s)',$referrer->getEmail()).'</div>';$order=Mage::getModel('sales/order')->loadByIncrementId($order_id);$toHtml .=  '<div class="arcanum-in-txt">'.$this->__('Referral order state: %s',$this->__($order->getState())).'</div>';} elseif ($order_id==Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_REVIEW){$toHtml .='<div class="arcanum-in-title">'.$this->__('Review points').'</div>';} elseif ($order_id < 0){$toHtml .='<div class="arcanum-in-title">'.$this->__('Gift').'</div>';} else {$toHtml .='<div class="arcanum-in-title">'.$this->__('Order: %s', $order_id).'</div>';$order=Mage::getModel('sales/order')->loadByIncrementId($order_id);$toHtml .='<div class="arcanum-in-txt">'.$this->__('Order state: %s',$this->__($order->getState())).'</div>';}return $toHtml;}}