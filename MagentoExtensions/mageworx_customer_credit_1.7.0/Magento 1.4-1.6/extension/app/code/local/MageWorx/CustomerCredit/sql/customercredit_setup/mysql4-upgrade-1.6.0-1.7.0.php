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
 * @copyright  Copyright (c) 2012 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

$installer = $this;
$installer->startSetup();

if (!$installer->getConnection()->tableColumnExists($installer->getTable('sales/invoice_grid'), 'base_customer_credit_amount')) {
    $installer->run("ALTER TABLE `{$this->getTable('sales/invoice_grid')}` ADD `base_customer_credit_amount` decimal(12,4) DEFAULT NULL;");    
}
$installer->run("UPDATE `{$this->getTable('sales/invoice_grid')}` AS sig, `{$this->getTable('sales/invoice')}` AS si
    SET sig.`base_customer_credit_amount` = si.`base_customer_credit_amount`
    WHERE sig.`entity_id` = si.`entity_id`");

if (!$installer->getConnection()->tableColumnExists($installer->getTable('sales/creditmemo_grid'), 'base_customer_credit_amount')) {
    $installer->run("ALTER TABLE `{$this->getTable('sales/creditmemo_grid')}` ADD `base_customer_credit_amount` decimal(12,4) DEFAULT NULL;");    
}
$installer->run("UPDATE `{$this->getTable('sales/creditmemo_grid')}` AS scmg, `{$this->getTable('sales/creditmemo')}` AS scm
    SET scmg.`base_customer_credit_amount` = scm.`base_customer_credit_amount`
    WHERE scmg.`entity_id` = scm.`entity_id`");


$installer->endSetup();