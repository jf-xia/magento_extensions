<?php
class Magestore_Groupdeal_Block_Sidebar extends Mage_Catalog_Block_Navigation {	
	public function getCategories(){
		$deals = Mage::helper('groupdeal')->getActiveDeals();
		$productIds = array();
		foreach($deals as $deal){
			$productIds = array_merge($productIds, $deal->getProductIds());
		}
		$productIds = array_unique($productIds);
		
		$categoryIds = array();
		foreach($productIds as $productId){
			$product = Mage::getModel('catalog/product')->load($productId);
			$categoryIds = array_merge($categoryIds, $product->getCategoryIds());
		}
		
		$categoryIds =  array_unique($categoryIds);
		
		$categories = Mage::getModel('catalog/category')->getCollection()
						->addFieldToFilter('entity_id', array('in' => $categoryIds))
						->addFieldToFilter('entity_id', array('neq' => Mage::app()->getStore()->getRootCategoryId()))
						->addAttributeToSelect('*');
		return $categories;
	}
	
	public function countDealInCategory($category){
		$dealIds = Mage::helper('groupdeal')->getDealIdsInCategory($category);
		return count(array_unique($dealIds));
	}
	
	public function getCategoryUrl($categoryId){
		return $this->getUrl('groupdeal/index/index', array('category'=>$categoryId));
	}
	
	public function getCategoryName(){
		$categoryId = $this->getRequest()->getParam('category');
		return Mage::getModel('catalog/category')->load($categoryId)->getName();
	}
	
	public function isShowNewestDealsInSidebar(){
		return Mage::getStoreConfig('groupdeal/general/newest_deals_in_sidebar');
	}
	
	public function isShowNewsletterFormInSidebar(){
		return Mage::getStoreConfig('groupdeal/general/newsletter_in_sidebar');
	}
	
	public function getNewestDeals(){
		$limit = Mage::getStoreConfig('groupdeal/general/number_of_deal_in_sidebar');
		$deals = Mage::helper('groupdeal')->getActiveDeals()
				->setOrder('start_datetime', 'DESC')
				->setPageSize($limit);
		return $deals;
	}
	
	public function drawChildren($_category){
		$html = '';
		foreach($_category->getChildren() as $_childCategory){
			$html .= '<option value="'. $_childCategory->getId() .'" selected="selected">';
			
			$level = $_category->getLevel();
			for($i = 1; $i < $level; $i++){
				$html .= '-  ';
			}
			
			$html .= $this->htmlEscape($_childCategory->getName());
			
			/* $_cat = Mage::getModel('catalog/category')->load($_childCategory->getId());
			$dealTotal = $this->countDealInCategory($_cat);
			if($dealTotal)
				$html .= ' (' . $dealTotal . ')'; */
				
			$html .= '</option>';
			
			//NEXT LVL
			$html .= $this->drawChildren($_childCategory);
		}
		return  $html;
    }
	
	public function setNewsleterUrl(){
		$currentUrl = $this->helper('core/url')->getCurrentUrl();
		Mage::getSingleton('core/session')->setNewsleterUrl($currentUrl);
	}
}