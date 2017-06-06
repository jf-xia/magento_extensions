<?php

class Topbuy_Ajax_Model_Prddescription extends Mage_Core_Model_Abstract
{
    protected function _construct(){
       $this->_init("ajax/prddescription");
    }

    public function getCategoryDesc($categoryArray)
    {
        $catDesc='';
        $currentCat = Mage::registry('current_category');
        if (isset($currentCat)) {
			 $categoryId = $currentCat->getId();            
			//$categoryId = Mage::getModel('homepage/categorymap')->getCollection()->addFilter('id_magcategory', $currentCat->getId())->getFirstItem()->getIdTbcategory();
			if(isset($categoryId))
			{ 
				$catDesc = $this->loadByCategory($categoryId)->getFirstItem();     
				}
                  
        }
        return $catDesc;
    }
//    public function getCategoryDesc($categoryArray)
//    {
//        $catDesc='';
//        $categoryId = Mage::getModel('homepage/categorymap')->getCollection()->addFilter('id_magcategory', $categoryArray[0])->getFirstItem()->getIdTbcategory();
//        
//        $catDesc = $this->loadByCategory($categoryId)->getFirstItem();
//        if (!$catDesc) {
//            $categoryId = Mage::getModel('catalog/category')->load($categoryId)->getParentCategory()->getId();
//            $catDesc = $this->loadByCategory($categoryId)->getFirstItem();
//        }
//        if (!$catDesc) {
//            $categoryId = Mage::getModel('catalog/category')->load($categoryId)->getParentCategory()->getId();
//            $catDesc = $this->loadByCategory($categoryId)->getFirstItem();
//        }
//        return $catDesc;
//    }

    public function loadByCategory($categoryId)
    {
        return $this->getCollection()->addFilter('catalogid', $categoryId);
    }

}
	 