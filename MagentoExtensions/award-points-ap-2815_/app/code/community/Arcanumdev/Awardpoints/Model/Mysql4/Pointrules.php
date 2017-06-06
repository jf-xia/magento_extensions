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
 class Arcanumdev_Awardpoints_Model_Mysql4_Pointrules extends Mage_Core_Model_Mysql4_Abstract{const SECONDS_IN_DAY=86400;public function _construct(){$this->_init('awardpoints/pointrules','rule_id');}public function _beforeSave(Mage_Core_Model_Abstract $object){if(!$object->getFromDate()){$date=Mage::app()->getLocale()->date();$date->setHour(0)->setMinute(0)->setSecond(0);$object->setFromDate($date);}if($object->getFromDate() instanceof Zend_Date){$object->setFromDate($object->getFromDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));}if(!$object->getToDate()){$object->setToDate(new Zend_Db_Expr('NULL'));}else{if($object->getToDate() instanceof Zend_Date){$object->setToDate($object->getToDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));}}parent::_beforeSave($object);}}