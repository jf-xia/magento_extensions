<?php
/**
 * INCHOO's FREE EXTENSION DISCLAIMER
 *
 * Please do not edit or add to this file if you wish to upgrade Magento
 * or this extension to newer versions in the future.
 *
 * Inchoo developers (Inchooer's) give their best to conform to
 * "non-obtrusive, best Magento practices" style of coding.
 * However, Inchoo does not guarantee functional accuracy of specific
 * extension behavior. Additionally we take no responsibility for any
 * possible issue(s) resulting from extension usage.
 *
 * We reserve the full right not to provide any kind of support for our free extensions.
 *
 * You are encouraged to report a bug, if you spot any,
 * via sending an email to bugreport@inchoo.net. However we do not guaranty
 * fix will be released in any reasonable time, if ever,
 * or that it will actually fix any issue resulting from it.
 *
 * Thank you for your understanding.
 */

/**
 * @category Inchoo
 * @package Inchoo_EmailCommunication
 * @author Branko Ajzele <ajzele@gmail.com>
 * @copyright Inchoo <http://inchoo.net>
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Enterprise_Banner_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Create table 'enterprise_banner/banner'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('inchoo_email_communication/log'))
    ->addColumn('log_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Log Id')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Created At')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 8, array(
        ), 'Status (success or failure)')
    ->addColumn('to_email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Send to')
    ->addColumn('subject', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Subject')
    /*
    ->addColumn('body', Varien_Db_Ddl_Table::TYPE_TEXT, '64K', array(
        ), 'Email body')
    */
    ->setComment('Inchoo_EmailCommunication Extension by Branko Ajzele, ajzele@gmail.com');
$installer->getConnection()->createTable($table);

$installer->endSetup();
