<?php

/**
 * Product controller
 * D:\DevProgram\wnmp\www\t5\app\code\core\Mage\Catalog\controllers\ProductController.php
 * @category   Mage
 * @package    Mage_Catalog
 */
//require_once 'Mage/Catalog/controllers/ProductController.php';
class Topbuy_Ajax_ImageajaxController extends Mage_Core_Controller_Front_Action {

    public function DetailsAction() {
        $productid = $this->getRequest()->getParam('pid');
        $_product = $this->getProduct($productid);
        echo $_product->getDescription();
    }

    public function ImagesAction() {
        $productid = $this->getRequest()->getParam('pid');
        $_product = $this->getProduct($productid);
        $images = $_product->getMediaGalleryImages();
        $imagesHtml ='';
        foreach ($images as $image) {
            if (file_exists($image->getPath())) {
                $imagesHtml .="<img src='" . $image->getUrl() . "' width='500' height='500' >";
            }
        }
        echo $imagesHtml;
    }

    public function getProduct($productid) {
        if (!Mage::registry('product')) {// && $this->getProductId()
            $product = Mage::getModel('catalog/product')->load($productid);
            Mage::register('product', $product);
        }
        return Mage::registry('product');
    }

}