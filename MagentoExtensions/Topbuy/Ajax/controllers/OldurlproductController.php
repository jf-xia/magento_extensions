<?php
require_once('app/code/local/Topbuy/Catalog/controllers/ProductController.php');

class Topbuy_Ajax_OldurlproductController extends Mage_Catalog_ProductController
{
    
    /**
     * Product view action
     */
    public function viewAction()
    {
        // Get initial data from request

		$urlPath = Mage::helper('ajax/oldurl')->curPageURL();
		$pattern = '/p([0-9]+).htm/i';	 
		preg_match($pattern,$urlPath, $matches);		
		$idproduct = $matches[1];
		
		$productmapModel = (int)Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('entity_id')->addAttributeToFilter('idtbproduct', $idproduct)->getFirstItem()->getId(); 
		  
		$productIdMag = $productmapModel; 
		//var_dump($productmapModel);
		//var_dump($productIdMag);
		
		
		//echo "You are on Older Product xxx URL function <br/> id is ".$matches[1]; 
		 
        // Get initial data from request
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId  = (int) $productIdMag;
        $specifyOptions = $this->getRequest()->getParam('options');		 
				
        // Prepare helper and params
        $viewHelper = Mage::helper('catalog/product_view');

        $params = new Varien_Object();
        $params->setCategoryId($categoryId);
        $params->setSpecifyOptions($specifyOptions);
		//var_dump($this);
        // Render page
        try {
            $viewHelper->prepareAndRender($productId, $this, $params);
			 
        } catch (Exception $e) {
			
            if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
                if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
                    $this->_redirect('');
                } elseif (!$this->getResponse()->isRedirect()) {
                    $this->_forward('noRoute');
                }
            } else {
                Mage::logException($e);
                $this->_forward('noRoute');
            }
        }
    }

  
}
 