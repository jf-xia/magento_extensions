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
 class Arcanumdev_Awardpoints_Model_Mysql4_Customer_Collection extends Mage_Customer_Model_Entity_Customer_Collection{protected function _initSelect(){parent::_initSelect(); $select = $this->getSelect(); $select ->from($this->getTable('awardpoints/awardpoints_account'),array(new Zend_Db_Expr('SUM('.$this->getTable('awardpoints/awardpoints_account').'.points_current) AS all_points_accumulated'),new Zend_Db_Expr('SUM('.$this->getTable('awardpoints/awardpoints_account').'.points_spent) AS all_points_spent'))) ->where($this->getTable('awardpoints/awardpoints_account').'.customer_id = e.entity_id'); if(version_compare(Mage::getVersion(), '1.4.0', '>=')){$select->where(" (".$this->getTable('awardpoints/awardpoints_account').".order_id = '".Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_REVIEW."' or '".Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_ADMIN."' or ".$this->getTable('awardpoints/awardpoints_account').".order_id = '".Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_REGISTRATION."' or ".$this->getTable('awardpoints/awardpoints_account').".order_id in (SELECT increment_id FROM ".$this->getTable('sales/order')." AS orders WHERE orders.state IN ('processing','complete')) ) ");}else{$table_sales_order = $this->getTable('sales/order').'_varchar'; $select->where(" (".$this->getTable('awardpoints/awardpoints_account').".order_id = '".Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_REVIEW."' or '".Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_ADMIN."' or ".$this->getTable('awardpoints/awardpoints_account').".order_id = '".Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_REGISTRATION."' or ".$this->getTable('awardpoints/awardpoints_account').".order_id in (SELECT increment_id FROM ".$this->getTable('sales/order')." AS orders WHERE orders.entity_id IN ( SELECT order_state.entity_id FROM ".$table_sales_order." AS order_state WHERE order_state.value <> 'canceled' AND order_state.value in ('processing','complete')) ) ) ");}if(Mage::getStoreConfig('awardpoints/default/points_duration', Mage::app()->getStore()->getId())){$select->where('( '.$this->getTable('awardpoints/awardpoints_account').'.date_end >= NOW() OR '.$this->getTable('awardpoints/awardpoints_account').'.date_end IS NULL)');}$select->group($this->getTable('awardpoints/awardpoints_account').'.customer_id'); return $this;}}