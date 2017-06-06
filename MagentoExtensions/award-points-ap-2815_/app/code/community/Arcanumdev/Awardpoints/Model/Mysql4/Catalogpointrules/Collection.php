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
 class Arcanumdev_Awardpoints_Model_Mysql4_Catalogpointrules_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract{public function _construct(){parent::_construct();$this->_init('awardpoints/catalogpointrules');}public function setValidationFilter($websiteId, $customerGroupId, $now=null){if(is_null($now)){$now = Mage::getModel('core/date')->date('Y-m-d');}$this->getSelect()->where('status=1');$this->getSelect()->where('find_in_set(?, website_ids)', (int)$websiteId);$this->getSelect()->where('find_in_set(?, customer_group_ids)', (int)$customerGroupId);$this->getSelect()->where('from_date is null or from_date<=?',$now);$this->getSelect()->where('to_date is null or to_date>=?',$now);$this->getSelect()->order('sort_order');return $this;}public function addWebsiteFilter($websiteIds){if(!is_array($websiteIds)){$websiteIds = array($websiteIds);}$parts = array();foreach ($websiteIds as $websiteId){$parts[] = $this->getConnection()->quoteInto('FIND_IN_SET(?, main_table.website_ids)',$websiteId);}if($parts){$this->getSelect()->where(new Zend_Db_Expr(implode(' OR ',$parts)));}return $this;}}