<?php
require_once "Mage/Catalog/controllers/ProductController.php";

class Topbuy_Catalog_ProductController extends Mage_Catalog_ProductController
{    
     public function viewAction()
    {
        // Get initial data from request
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId  = (int) $this->getRequest()->getParam('id');
        $specifyOptions = $this->getRequest()->getParam('options');		 
		
		//add by richard to record avail track
	    $emark_tracking_code  = (string) $this->getRequest()->getParam('emark_tracking_code',"");
		Mage::register('emark_tracking_code', $emark_tracking_code);
		//end
		
	 	//Mage::getSingleton('catalog/product_avail')->setJoke($joke);
        // Prepare helper and params
        $viewHelper = Mage::helper('catalog/product_view');
        $params = new Varien_Object();
        $params->setCategoryId($categoryId);
        $params->setSpecifyOptions($specifyOptions);
	 
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
?>