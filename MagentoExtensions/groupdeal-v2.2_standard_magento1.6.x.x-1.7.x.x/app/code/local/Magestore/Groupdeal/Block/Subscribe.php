<?php
class Magestore_Groupdeal_Block_Subscribe extends Mage_Catalog_Block_Navigation
{	
	
	public function _prepareLayout(){
		$headBlock = $this->getLayout()->getBlock('head');
		$headBlock->setTitle($this->__('Subscribe groupdeal'));
		return parent::_prepareLayout();
    }
	
	public function countDealInCategory($categoryId){
		$dealIds = Mage::helper('groupdeal')->getActiveDealIds();
		$dealcategories = Mage::getModel('groupdeal/dealcategory')->getCollection()
						->addFieldToFilter('deal_id', array('in'=>$dealIds))
						->addFieldToFilter('category_id', $categoryId);
		return count($dealcategories);
	}
	
	public function setNewsleterUrl(){
		$currentUrl = $this->getUrl('groupdeal');
		Mage::getSingleton('core/session')->setNewsleterUrl($currentUrl);
	}
	
	public function getEmail(){
		$encodeEmail = $this->getRequest()->getParam('e');
		return base64_decode($encodeEmail);
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
			$html .= '</option>';
			
			//NEXT LVL
			$html .= $this->drawChildren($_childCategory);
		}
		return  $html;
    }
}