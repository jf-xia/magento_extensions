<?php
class Magestore_Groupdeal_Block_Categories extends Mage_Core_Block_Template{	
	
	public function getCategories(){
		$dealIds = Mage::helper('groupdeal')->getActiveDealIds();
		
		$dealcategories = Mage::getModel('groupdeal/dealcategory')->getCollection()
						->addFieldToFilter('deal_id', array('in'=>$dealIds))
						;
		
		$categoryIds = array();
		foreach($dealcategories as $dealcategory){
			$categoryIds[] = $dealcategory->getCategoryId();
		}

		$categories = Mage::getModel('catalog/category')->getCollection()
						->addFieldToFilter('entity_id', array('in' => $categoryIds))
						->addFieldToFilter('entity_id', array('neq' => Mage::app()->getStore()->getRootCategoryId()))
						->addAttributeToSelect('*');
		return $categories;
	}
	
	public function countDealInCategory($categoryId){
		$dealIds = Mage::helper('groupdeal')->getActiveDealIds();
		$dealcategories = Mage::getModel('groupdeal/dealcategory')->getCollection()
						->addFieldToFilter('deal_id', array('in'=>$dealIds))
						->addFieldToFilter('category_id', $categoryId);
		return count($dealcategories);
	}
	
	public function getCategoryUrl($categoryId){
		return $this->getUrl('groupdeal/index/index', array('category'=>$categoryId));
	}
}