<?php
class PWS_ProductQA_Model_Mysql4_Productqa_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        $this->_init('pws_productqa/productqa');
    }

    public function joinProducts()
    {
        $resource = Mage::getSingleton('core/resource');
        $product_table = $resource->getTableName('catalog/product');

        $productResource = Mage::getResourceSingleton('catalog/product');
        $nameAttr = $productResource->getAttribute('name');
        $nameAttrId = $nameAttr->getAttributeId();

        $nameAttrTable = $nameAttr->getBackend()->getTable();

        //yep, the wonderful world of magento EAV,
        //two joins because if we don't have value for name for selected store then we have to use the default value
        //I've only lost an hour with this stupid query
        $this->getSelect()->joinLeft(
                array('_table_product_name' => $nameAttrTable),
                '_table_product_name.entity_id=main_table.product_id
                    AND (_table_product_name.store_id = main_table.store_id)
                    AND _table_product_name.attribute_id = '.(int)$nameAttrId,
               array('product_store_id'=>'_table_product_name.store_id')
            )->joinLeft(
                array('_table_product_name2' => $nameAttrTable),
                '_table_product_name2.entity_id = main_table.product_id
                    AND (_table_product_name2.store_id = 0)
                    AND _table_product_name2.attribute_id = '.(int)$nameAttrId,
               array('')
            )
            ->from("",array(
                        'product_name' => new Zend_Db_Expr('IFNULL(_table_product_name.value,_table_product_name2.value)')
                        )
        );


    	 //echo ($this->getSelect()->__toString());

         return $this;
    }

    public function joinStore()
    {
        $resource = Mage::getSingleton('core/resource');
        $store_table = $resource->getTableName('core_store');

        $this->getSelect()->joinInner(array('_table_store' => $store_table),
        '_table_store.store_id=main_table.store_id', array('store_code'=>'code', 'store_name'=>'name'));

        return $this;
    }


}
