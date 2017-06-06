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
 class Arcanumdev_Awardpoints_Model_Review extends Mage_Review_Model_Review{public function aggregate(){if($this->isApproved()){if($pointsInt=Mage::getStoreConfig('awardpoints/registration/review_points', Mage::app()->getStore()->getId())){if($this->getCustomerId()){$award_model=Mage::getModel('awardpoints/stats');$data=array('customer_id'=>$this->getCustomerId(),'store_id'=>$this->getStoreId(),'points_current'=>$pointsInt,'order_id'=>Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_REVIEW);$award_model->setData($data);$award_model->save();}}}parent::aggregate();return $this;}}