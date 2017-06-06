<?php
class Mage_Productlist_Block_List_Category extends Mage_Core_Block_Template {

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
		$helper = Mage::helper('catalog/category');
		$collection = $helper->getStoreCategories('name', true, false);
		$this->setCollection($collection);
	}
}