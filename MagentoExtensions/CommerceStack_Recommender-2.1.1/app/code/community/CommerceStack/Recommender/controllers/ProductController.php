<?php
include("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class CommerceStack_Recommender_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    public function relatedAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        
        //$layout = $this->getLayout();
        //$block = $layout->getBlock('catalog.product.edit.tab.related');
        //$block->setProductsRelated($this->getRequest()->getPost('products_related', null));
        $this->renderLayout();
    }

    public function relatedCrosssell()
    {
        $this->_initProduct();
        $this->loadLayout();
        
        //$layout = $this->getLayout();
        //$block = $layout->getBlock('catalog.product.edit.tab.crosssell');
        //$block->setProductsRelated($this->getRequest()->getPost('products_related', null));
        $this->renderLayout();
    }

}