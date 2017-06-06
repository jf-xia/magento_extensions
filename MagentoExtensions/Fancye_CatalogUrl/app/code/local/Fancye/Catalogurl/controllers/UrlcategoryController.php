<?php
require_once('app/code/core/Mage/Catalog/controllers/CategoryController.php');

class Fancye_Catalogurl_UrlcategoryController extends Mage_Catalog_CategoryController
{ 
    public function viewAction()
    {
				$urlPath = Mage::helper('catalogurl/oldurl')->curPageURL();
				$pattern = '/c([0-9]+)-/i';	 
				preg_match($pattern,$urlPath, $matches);		
				$idcategory = $matches[1];
				$category = Mage::getModel('catalog/category')->load($idcategory);
				$categoryId = (int)$category->getId();
				$categoryUrlKey = $category->getUrlKey().'.html';
				$categoryUrl = Mage::getBaseUrl().'c'.$categoryId.'-'.$categoryUrlKey;
				//echo $categoryUrl;
				//var_dump($productmapModel);
				//var_dump($productIdMag);
				if (!strpos($urlPath,$categoryUrlKey)){
					//echo $productUrl;
					header('HTTP/1.1 301 Moved Permanently'); 
					header("location:".$categoryUrl); 
					die();
					//$this->_redirect($productUrl);
					//$this->getResponse()->setRedirect(Mage::getUrl($productUrl));
				}
					
				$this->_forward('view','category','catalog',array('id'=>$categoryId));
    }

	
}