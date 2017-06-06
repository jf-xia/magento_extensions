<?php
/**
 * Diglin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Diglin
 * @package     CKApps_Phonenumber
 * @copyright   Copyright (c) 2011 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer CKApps_Phonenumber_Model_Entity_Setup */
$installer = $this;

/* @var $eavConfig Mage_Eav_Model_Config */
$eavConfig = Mage::getSingleton('eav/config');
$phonenumberAttribute = $eavConfig->getAttribute('customer', 'phonenumber');

$installer->startSetup();

$result = $installer->getConnection()->raw_fetchRow("SHOW COLUMNS from {$this->getTable('sales_flat_order')} like '%customer_phonenumber%'");
if(!in_array('customer_phonenumber', $result)){
$installer->run("
    ALTER TABLE  `{$this->getTable('sales_flat_order')}`
    	ADD  `customer_phonenumber` VARCHAR( 255 ) NULL AFTER  `customer_taxvat`
    ");
    // can be a fix for bug of this module in Magento > 1.5
}


$select = new Zend_Db_Select($installer->getConnection());
$select->from(array('c' => $this->getTable('customer_entity')), 'email')
    ->joinLeft(array('cev' => $this->getTable('customer_entity_varchar')), 'c.entity_id = cev.entity_id')
    ->where("cev.entity_id NOT IN (SELECT entity_id FROM `{$this->getTable('customer_entity_varchar')}` WHERE attribute_id = 952)")
    ->group('c.entity_id');

// Create phonenumber for old customers to prevent problem when creating an order
$customers = $installer->getConnection()->fetchAll($select);
foreach ($customers as $customer){
    $customer['attribute_id'] = $phonenumberAttribute->getId();
    $email = $customer['email'];
    $pos = strpos($email, '@');
    $customer['value'] = substr($email, 0, $pos) . substr(uniqid(), 0, 5);
    unset($customer['email']);
    unset($customer['value_id']);
    
    $installer->getConnection()->insert($this->getTable('customer_entity_varchar'), $customer);
}

$installer->endSetup();