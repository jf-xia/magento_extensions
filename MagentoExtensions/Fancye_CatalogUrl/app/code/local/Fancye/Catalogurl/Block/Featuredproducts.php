<?php
class Fancye_Catalogurl_Block_Featuredproducts extends Mage_Catalog_Block_Product_Abstract{
	protected $_productsCount = null;
	const DEFAULT_PRODUCTS_COUNT =4;
	protected function _beforeToHtml(){
		$collection = Mage::getResourceModel('catalog/product_collection');
		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
		Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

		$collection = $collection->addAttributeToSelect('*')
            ->addAttributeToSelect(array('name', 'price', 'small_image'))
            ->setStoreId(Mage::app()->getStore()->getId())
            //->addStoreFilter($storeId)
			->addAttributeToFilter('status',1)
            ->addAttributeToFilter('light_featured_product', array('eq' => '1'))
			->addAttributeToSort('updated_at', 'desc')
			->setPageSize(self::DEFAULT_PRODUCTS_COUNT)
			->setCurPage(1)
			->load();
		$this->setProductCollection($collection);
		return parent::_beforeToHtml();
	}
}