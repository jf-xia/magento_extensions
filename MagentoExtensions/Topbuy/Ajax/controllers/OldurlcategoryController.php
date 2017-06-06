<?php
require_once('app/code/core/Mage/Catalog/controllers/CategoryController.php');

class Topbuy_Ajax_OldurlcategoryController extends Mage_Catalog_CategoryController
{ 
	public function IndexAction() {		 
		$this->viewAction();
	}
	
	protected function _initCatagory()
    {
		$urlPath = Mage::helper('ajax/oldurl')->curPageURL();
		$pattern = '/-c([0-9]+).htm/i';	 
		preg_match($pattern,$urlPath, $matches);		
		$idcategorytb = $matches[1];
		/*
 		$categorymapModel =  Mage::getModel('homepage/categorymap')->getCollection()->addFilter('id_tbcategory', $idcategorytb)->getFirstItem()->getIdMagcategory(); 
       */
		$categorymapModel = Mage::getModel('homepage/categorymap')->getCollection()->addFilter('id_tbcategory', $idcategorytb);
		
		 
     	$idcategorymag = 0;
	    foreach ($categorymapModel as $cate){
           $idcategory=$cate->getIdMagcategory();
		 
            if (strpos(Mage::getModel('catalog/category')->load($idcategory)->getPath(), '/3/')){
                $idcategorymag = $idcategory;
            }
        }
		 $categoryId = 0;
        Mage::dispatchEvent('catalog_controller_category_init_before', array('controller_action' => $this));
		if ($idcategorymag > 0)
		{
			$categoryId = (int) $idcategorymag;
			}
        
		//echo "here".$categoryId;
		//die;
        if (!$categoryId) {
            return false;
        }
		
        $category = Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($categoryId);

        if (!Mage::helper('catalog/category')->canShow($category)) {
            return false;
        }
        Mage::getSingleton('catalog/session')->setLastVisitedCategoryId($category->getId());
        Mage::register('current_category', $category);
        try {
            Mage::dispatchEvent(
                'catalog_controller_category_init_after',
                array(
                    'category' => $category,
                    'controller_action' => $this
                )
            );
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            return false;
        }

        return $category;
    }
	 
	 /**
     * Category view action
     */
    public function viewAction()
    {
        if ($category = $this->_initCatagory()) {
            $design = Mage::getSingleton('catalog/design');
            $settings = $design->getDesignSettings($category);

            // apply custom design
            if ($settings->getCustomDesign()) {
                $design->applyCustomDesign($settings->getCustomDesign());
            }

            Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());

            $update = $this->getLayout()->getUpdate();
            $update->addHandle('default');

            if (!$category->hasChildren()) {
                $update->addHandle('catalog_category_layered_nochildren');
            }

            $this->addActionLayoutHandles();
            $update->addHandle($category->getLayoutUpdateHandle());
            $update->addHandle('CATEGORY_' . $category->getId());
            $this->loadLayoutUpdates();

            // apply custom layout update once layout is loaded
            if ($layoutUpdates = $settings->getLayoutUpdates()) {
                if (is_array($layoutUpdates)) {
                    foreach($layoutUpdates as $layoutUpdate) {
                        $update->addUpdate($layoutUpdate);
                    }
                }
            }

            $this->generateLayoutXml()->generateLayoutBlocks();
            // apply custom layout (page) template once the blocks are generated
            if ($settings->getPageLayout()) {
                $this->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
            }

            if ($root = $this->getLayout()->getBlock('root')) {
                $root->addBodyClass('categorypath-' . $category->getUrlPath())
                    ->addBodyClass('category-' . $category->getUrlKey());
            }

            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();
        }
        elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    } 
}