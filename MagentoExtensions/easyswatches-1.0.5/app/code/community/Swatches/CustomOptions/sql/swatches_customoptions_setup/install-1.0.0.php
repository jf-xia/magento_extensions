<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('swatches_customoptions/images'))
    ->addColumn('image_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Image ID')
    ->addColumn('option_type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Option Type ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Store ID')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Title')
    ->addIndex(
        $installer->getIdxName('swatches_customoptions/images', array('option_type_id', 'store_id'), 
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('option_type_id', 'store_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('swatches_customoptions/images', array('option_type_id')),
        array('option_type_id'))
    ->addIndex($installer->getIdxName('swatches_customoptions/images', array('store_id')),
        array('store_id'))
    ->addForeignKey(
        $installer->getFkName(
            'swatches_customoptions/images',
            'option_type_id',
            'catalog/product_option_type_value',
            'option_type_id'
        ),
        'option_type_id', $installer->getTable('catalog/product_option_type_value'), 'option_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('swatches_customoptions/images', 'store_id', 'core_store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Custom Options Images Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();
