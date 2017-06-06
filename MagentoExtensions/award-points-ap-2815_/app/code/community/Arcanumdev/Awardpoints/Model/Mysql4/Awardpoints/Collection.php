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
 class Arcanumdev_Awardpoints_Model_Mysql4_Awardpoints_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract{public function _construct(){parent::_construct();$this->_init('awardpoints/stats');}protected function _initSelect(){parent::_initSelect();$select=$this->getSelect();$select->join(array('cust'=>$this->getTable('awardpoints/customer_entity')),'customer_id=cust.entity_id');return $this;}public function joinUser(){$this->getSelect()->joinLeft($this->getTable('arcanumbooster/customer_entity'),$this->getTable('arcanumbooster/customer_entity').".entity_id=main_table.customer_id",array('email'));return $this;}public function joinValidOrders($customer_id){$order_states=array("processing","complete","new");$this->getSelect()->joinLeft(array('ord'=>$this->getTable('sales/order')),'main_table.order_id=ord.entity_id');$this->getSelect()->where('ord.customer_id=?',$customer_id);$this->getSelect()->where('state in (?)', implode(',',$order_states));return $this;}public function addCustomerFilter($id){$this->getSelect()->where('customer_id=?',$id);return $this;}public function addOrderFilter($id){$this->getSelect()->where('order_id=?',$id);return $this;}public function addStoreFilter($id){$this->getSelect()->where('store_id=?',$id);return $this;}}