<?php 
class Fancye_Catalogurl_Block_Listing extends Mage_Catalog_Block_Product_Abstract{
	public function __construct(){
		//$this->setTemplate('featuredproducts/block_featured_products.phtml');
		$this->setLimit(40);
		$sort_by = 1;
		//$this->setItemsPerRow((int)Mage::getStoreConfig("featuredproducts/general/number_of_items_per_row"));
		switch ($sort_by) {
			case 0:
				$this->setSortBy("rand()");
			break;
			case 1:
				$this->setSortBy("created_at desc");
			break;
			default:
				$this->setSortBy("rand()");
		}
	}
	public function getCategoriesPorduct($categoriesObj){
		$collection = Mage::getResourceModel('catalog/product_collection');
		$attributes = Mage::getSingleton('catalog/config')->getProductAttributes();
		$collection->addAttributeToSelect($attributes)
			->addCategoryFilter($categoriesObj)
			->addMinimalPrice()
			->addFinalPrice()
			->addTaxPercents()
			->addAttributeToFilter('light_featured_product', 1, 'left')
			->addStoreFilter()
			->getSelect()->order($this->getSortBy())->limit($this->getLimit());
		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
		Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
		$collection->load();
		return $collection;
	}
	public function getFilterPorduct(){
		$currCat = Mage::registry('current_category');
		    	$cacollection = Mage::getModel('catalog/category')
		    	->getCategories($currCat->getId(),0,false,true,true);
		    	$productArr = array();
		    	
				foreach ($cacollection as $cat){
		    		$getProductCollection = $this->getCategoriesPorduct($cat);
		    		$count = $cat->getProductCount();
		    		$newcount = $newcount +$count;
		    		if($count){
		    			$productArr[] = $getProductCollection;
		    		}
		    		if($newcount>=$this->getLimit())break;
		    	}
		return $productArr;
	}
	public function getByCategoryproduct(){
		$productArr = array();
		$collection = Mage::getResourceModel('catalog/product_collection');
		$attributes = Mage::getSingleton('catalog/config')->getProductAttributes();
		$collection->addAttributeToSelect($attributes)
			->addMinimalPrice()
			->addFinalPrice()
			->addTaxPercents()
			->addAttributeToFilter('light_featured_product', 1, 'left')
			->addStoreFilter()
			->getSelect()->order($this->getSortBy())->limit($this->getLimit());
		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
		Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
		$collection->load();
		$productArr[] =$collection;
		return $productArr;
	}
	public function getCurrentAndChildFeatured(){
		$filter_by_category = 141;//Mage::getStoreConfig("featuredproducts/general/filter_by_category");
		switch ($filter_by_category){
			case 0:
				$productArr = $this->getByCategoryproduct();
			break;
			case 1:
				$productArr = $this->getFilterPorduct();
			break;
			default:
				$productArr = $this->getByCategoryproduct();
		}
    	return $productArr;
	}
	
	
	/*
	protected function _beforeToHtml(){
		$collection = Mage::getResourceModel('catalog/product_collection');
		//$currCat = Mage::registry('current_category');
		//$currentCategoryId = $currCat->getEntityId();
		//$collection->addCategoryFilter($currCat);
		
		$attributes = Mage::getSingleton('catalog/config')
			->getProductAttributes();

		$collection->addAttributeToSelect($attributes)
			//->addAttributeToFilter('category_id',array('finset'=>'5,15'))
			->addMinimalPrice()
			->addFinalPrice()
			->addTaxPercents()
			->addAttributeToFilter('light_featured_product', 1, 'left')
			->addStoreFilter()
			->getSelect()->order($this->getSortBy())->limit($this->getLimit());

		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
		Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
	
		$this->_productCollection = $collection;

		$this->setProductCollection($collection);
		return parent::_beforeToHtml();
	}
	protected function getBlockLabel(){
		return $this->helper('featuredproducts')->getCmsBlockLabel();
	}*/
}