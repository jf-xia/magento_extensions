<?php
require_once('app/code/core/Mage/Catalog/controllers/ProductController.php');

class Fancye_Catalogurl_UrlproductController extends Mage_Catalog_ProductController
{
    
    /**
     * Product view action
     */
    public function viewAction()
    {
        // Get initial data from request
				$urlPath = Mage::helper('catalogurl/oldurl')->curPageURL();
				$pattern = '/p([0-9]+)-/i';	 
				preg_match($pattern,$urlPath, $matches);		
				$idproduct = $matches[1];
				
				$product = Mage::getModel('catalog/product')->load($idproduct);
				$productId = (int)$product->getId();
				$productUrlKey = $product->getUrlKey().'.html';
				$productUrl = Mage::getBaseUrl().'p'.$productId.'-'.$productUrlKey;
				//var_dump($productmapModel);
				//var_dump($productIdMag);
				if (!strpos($urlPath,$productUrlKey)){
					//echo $productUrl;
					header('HTTP/1.1 301 Moved Permanently'); 
					header("location:".$productUrl); 
					die();
					//$this->_redirect($productUrl);
					//$this->getResponse()->setRedirect(Mage::getUrl($productUrl));
				}
					
				$this->_forward('view','product','catalog',array('id'=>$productId));
    }

  
}
 