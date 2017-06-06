<?php
class Magestore_Groupdeal_Block_Deallist extends Mage_Catalog_Block_Product_Abstract
{	
	const WAITING 	= 6;
	const OPENING 	= 5;
	const REACHED 	= 4;
	const UNREACHED	= 3;
	const END 		= 2;
	const ENABLED 	= 1;
	const DISABLED	= 0;
	
	public function _prepareLayout(){		
		$headBlock = $this->getLayout()->getBlock('head');
		$headBlock->setTitle($this->getTitle());		
		return parent::_prepareLayout();
    }
	
    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }	
	
	protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $layer = Mage::getSingleton('catalog/layer');
			$store_id = Mage::app()->getStore()->getId();
			$categoryId = $this->getRequest()->getParam('category');
            $productIds = Mage::helper('groupdeal')->getActiveGroupdealProductIds($categoryId);
			
			$this->_productCollection = Mage::getResourceModel('catalog/product_collection')
											->setStoreId($this->getStoreId())
											->addFieldToFilter('entity_id',array('in'=>$productIds))
											->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
									//		->addMinimalPrice()
									//		->addTaxPercents()
											->addStoreFilter()
											->setOrder('groupdeal_featured', 'DESC')
											->setOrder('groupdeal_endtime', 'ASC');
																			
		}
		
        return $this->_productCollection;
    }
	
	public function getColumnCount(){
		return 3;
	}
	
	public function getDealUrl($productId){
		$deal = $this->getDeal($productId);
		return $deal->getDealUrl();
	}
	
	public function getDeal($productId){
		$deal = Mage::getModel('groupdeal/deal')->loadDealByProduct($productId);
		return $deal;
	}
	
	public function getOneLineTitle($title, $len){
		if(strlen($title) <= $len)
			return $title;
		else
			return substr($title, 0, $len) . '...';
	}
	
	public function getCategoryName(){
		$categoryId = $this->getRequest()->getParam('category');
		return Mage::getModel('catalog/category')->load($categoryId)->getName();
	}
	
	public function getTitle(){
		$categoryId = $this->getRequest()->getParam('category');
		$categoryName = Mage::getModel('catalog/category')->load($categoryId)->getName();
		if($categoryId)
			return Mage::helper('groupdeal')->__('Deals in ') . $categoryName;
		else
			return Mage::helper('groupdeal')->__('All Deals');
	}
	
	//Hai.Ta
	public function getActionUrlBuy(){
		return $this->getUrl('groupdeal/index/getProductOption');
	}
	
	public function getProductHaveOptions($ids){
		$products = array();
		$_productsInDeal = $this->getProductsInDeal($ids);
		foreach ($_productsInDeal as $product){							
			if(Mage::helper('groupdeal')->checkTypeProduct($product->getTypeId())){		
				
				$products[] = $product;
			}
		}		
		if(count($products) == 0) return 0;		
		return $products[0];
	}
		
	public function getProductsInDeal($ids){
		// $productIds = $this->getProductIdsInDeal($id);				
		$products = array();
		$collection = Mage::getModel('catalog/product')->getCollection()
					->addFieldToFilter('entity_id', array('in'=>$ids))
					->addAttributeToSelect('*')
					->addAttributeToSort('price', 'DESC');
		if(count($collection)){
			foreach($collection as $item){				
				$products[] = $item;
			}
		}
		
		return $products;
	}	
}