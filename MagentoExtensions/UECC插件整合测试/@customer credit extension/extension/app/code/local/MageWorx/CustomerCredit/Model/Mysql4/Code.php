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

class MageWorx_CustomerCredit_Model_Mysql4_Code extends Mage_Core_Model_Mysql4_Abstract
{
	protected function _construct()
	{
		$this->_init('customercredit/code', 'code_id');
	}
	
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{
        $date = Mage::app()->getLocale()->date();
        $dateFull = clone $date;
        $date->setHour(0)
            ->setMinute(0)
            ->setSecond(0);
        if (!$object->getFromDate()) {
            $object->setFromDate($date);
        }
        if ($object->getFromDate() instanceof Zend_Date) {
            $object->setFromDate($object->getFromDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        }

        if (!$object->getToDate()) {
            $object->setToDate(new Zend_Db_Expr('NULL'));
        }
        else {
            if ($object->getToDate() instanceof Zend_Date) {
                $object->setToDate($object->getToDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            }
        }
        
        if (!$object->getId()) {
            $object->setCreatedDate($dateFull);
        }
        if ($object->getCreatedDate() instanceof Zend_Date) {
            $object->setCreatedDate($object->getCreatedDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        }
        $object->setUpdatedDate($dateFull);
        if ($object->getUpdatedDate() instanceof Zend_Date) {
            $object->setUpdatedDate($object->getUpdatedDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        }
        return parent::_beforeSave($object);
	}
}