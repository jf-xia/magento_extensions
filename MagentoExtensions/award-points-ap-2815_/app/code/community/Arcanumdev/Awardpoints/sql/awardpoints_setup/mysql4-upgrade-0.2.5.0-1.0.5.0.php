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
 $connection = Mage::getSingleton('core/resource') ->getConnection('awardpoints_read'); $select = $connection->select() ->from('information_schema.COLUMNS') ->where("COLUMN_NAME='awardpoints_referral_id' AND TABLE_NAME='{$this->getTable('awardpoints_account')}'"); $data = $connection->fetchRow($select); if(!isset($data['COLUMN_NAME'])){ $installer = $this; $installer->startSetup(); $installer->run(" ALTER TABLE {$this->getTable('awardpoints_account')} ADD COLUMN `awardpoints_referral_id` INT( 11 ) NULL DEFAULT NULL; "); $installer->endSetup(); }