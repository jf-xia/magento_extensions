<?php

class Luxe_Bestsellers_Model_Resource_Eav_Mysql4_Product extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product {
	public function getParentProductIds($object)
    {
        $childId = $object->getId();

        $groupedProductsTable = $this->getTable('catalog/product_link');
        $groupedLinkTypeId = Mage_Catalog_Model_Product_Link::LINK_TYPE_GROUPED;

        $configurableProductsTable = $this->getTable('catalog/product_super_link');

        $groupedSelect = $this->_getReadAdapter()->select()
            ->from(array('g'=>$groupedProductsTable), 'g.product_id')
            ->where("g.linked_product_id = ?", $childId)
            ->where("link_type_id = ?", $groupedLinkTypeId);

        $groupedIds = $this->_getReadAdapter()->fetchCol($groupedSelect);

        $configurableSelect = $this->_getReadAdapter()->select()
            ->from(array('c'=>$configurableProductsTable), 'c.parent_id')
            ->where("c.product_id = ?", $childId);

        $configurableIds = $this->_getReadAdapter()->fetchCol($configurableSelect);
        return array_merge($groupedIds, $configurableIds);
    }
}