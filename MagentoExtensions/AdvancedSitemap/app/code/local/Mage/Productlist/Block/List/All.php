<?php
class Mage_Productlist_Block_List_All extends Mage_Catalog_Block_Product_Abstract {

	public function bindPager($pagerName) {
		$pager = $this->getLayout()->getBlock($pagerName);
		if ($pager) {
			$pager->setAvailableLimit(array(10 => 10,20 => 20,50 => 50,100 => 100));
			$pager->setCollection($this->getCollection());
			$pager->setShowPerPage(true);
		}
	}

	public function __construct() {
		parent::__construct();

		$storeId = Mage::app()->getStore()->getId();
		$products = Mage::getResourceModel('catalog/product_collection')
			->setStoreId($storeId)
			->addAttributeToSelect('*')
			->addStoreFilter($storeId);

		Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);

		$this->setCollection($products);
	}
}