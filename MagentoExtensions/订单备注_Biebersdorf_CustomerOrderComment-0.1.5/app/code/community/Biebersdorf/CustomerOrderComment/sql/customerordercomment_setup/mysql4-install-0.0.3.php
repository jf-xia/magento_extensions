<?php
/**
 * Magento
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
 * @category   Biebersdorf
 * @package    Biebersdorf_CustomerOrderComment
 * @copyright  Copyright (c) 2009 Ottmar Biebersdorf (http://www.obiebersdorf.de)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0) 
 */

$installer = $this;
/* @var $installer Mage_Sales_Model_Mysql4_Setup */

$installer->startSetup();

// Get ID of the entity model 'sales/order'.
$sql = 'SELECT entity_type_id FROM '.$this->getTable('eav_entity_type').' WHERE entity_type_code="order"';
$row = Mage::getSingleton('core/resource')
		 ->getConnection('core_read')
		->fetchRow($sql);

// Create EAV-attribute for the order comment.
$c = array (
  'entity_type_id'  => $row['entity_type_id'],
  'attribute_code'  => 'biebersdorf_customerordercomment',
  'backend_type'    => 'text',     // MySQL-Datatype
  'frontend_input'  => 'textarea', // Type of the HTML form element
  'is_global'       => '1',
  'is_visible'      => '1',
  'is_required'     => '0',
  'is_user_defined' => '0',
  'frontend_label'  => 'Customer Order Comment',
);
$attribute = new Mage_Eav_Model_Entity_Attribute();
$attribute->loadByCode($c['entity_type_id'], $c['attribute_code'])
		  ->setStoreId(0)
		  ->addData($c);
$attribute->save();

$installer->endSetup();
