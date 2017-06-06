<?php

class Topbuy_Avail_IndexController extends Mage_Core_Controller_Front_Action {

//    public function IndexAction() {
//      
//	  $this->loadLayout();   
//	  $this->getLayout()->getBlock("head")->setTitle($this->__("Avail"));
//	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
//      $breadcrumbs->addCrumb("home", array(
//                "label" => $this->__("Home Page"),
//                "title" => $this->__("Home Page"),
//                "link"  => Mage::getBaseUrl()
//		   ));
//
//      $breadcrumbs->addCrumb("avail", array(
//                "label" => $this->__("Avail"),
//                "title" => $this->__("Avail")
//		   ));
//
//      $this->renderLayout(); 
//	  
//    }


//    public function tAction() {
//        $idproduct = Mage::getSingleton('core/cookie')->get("topbuy_cookie_customerr");
//        $idtbProduct = Mage::getModel('catalog/product')->load($idproduct)->getIdtbproduct();
//        $productAvail = Mage::getModel('avail/productavail')->getCollection()->addFilter('idsource', $idtbProduct)
//                ->addFilter('referType', 1)->addFilter('sourceType', 0)->setOrder('position','ASC')->setPageSize(10);
//        foreach ($productAvail as $item) {
//            echo $item->getIdrefer();
//            echo "<br>";
//        }
//    }
    
    public function homelvAction() {
        $_coreHelper = Mage::helper('core');
        $index_id = 0;
        $idproduct = Mage::getSingleton('core/cookie')->get("topbuy_cookie_customerr");
        $product = Mage::getModel('catalog/product')->load($idproduct);
        $idtbProduct = $product->getIdtbproduct();
        $productAvail = Mage::getModel('avail/productavail')->getCollection()->addFilter('idsource', $idtbProduct)
                ->addFilter('referType', 1)->addFilter('sourceType', 0)->setOrder('position','ASC');
        $productAvail->getSelect()->group('idrefer')->limit(10);
        
        if ($productAvail->getFirstItem()->hasData()&&$product->getStatus()==1) {
            $outputHtml = '<div class="topbuy-home-block">
                            <h2>Recommendations</h2>
                            <div id="topbuy-home-recommendation">
                                <ul id="topbuy-rmd-carousel" class="topbuy-jcarousel-skin-home-rmd">';
            
                            $outputHtml = $outputHtml . ' 
                            <li>
                                <a href="' . $product->getProductUrl() . '" title="" target="_blank" >
                                    <img src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getSmallImage() . '" width="120" height="120" alt="" />
                                    <h4>' . $product->getName() . '</h4>
                                    <p>' . $_coreHelper->currency($product->getPrice(), true, false) . '&nbsp;&nbsp;&nbsp;<span>RRP:<del>' . $_coreHelper->currency($product->getListprice(), true, false) . '</del></span></p>
                                </a>
                            </li>';

            foreach ($productAvail as $item) {
                $_product_id = $item->getIdrefer();
                if ($_product_id != "" && $_product_id != 0) {
                    try {
                        $product = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')
                                        ->addAttributeToFilter('idtbproduct', $_product_id)->getFirstItem();
//                        $product = Mage::getModel('catalog/product')->load($_product_id);

                        if ($product->hasData()&&Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty()>0) {
                            $imageURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getSmallImage();
                            $productURL = $product->getProductUrl();
                            $salePrice = $_coreHelper->currency($product->getPrice(), true, false);
                            $productName = $product->getName();
                            $listPrice = $_coreHelper->currency($product->getListprice(), true, false);
                            $outputHtml = $outputHtml . ' 
                            <li>
                                <a href="' . $productURL . '" title="" target="_blank" >
                                    <img src="' . $imageURL . '" width="120" height="120" alt="" />
                                    <h4>' . $productName . '</h4>
                                    <p>' . $salePrice . '&nbsp;&nbsp;&nbsp;<span>RRP:<del>' . $listPrice . '</del></span></p>
                                </a>
                            </li>';
                            $index_id++;
                        }
                    } catch (Mage_Core_Exception $e) {
                        
                    }
                }
            }

            $outputHtml = $outputHtml . '</ul></div></div>';
        } else if ($index_id <= 1) {
            //display default 8 products
//			$_helper = $this->helper('catalog/output');
            $productCollection = Mage::getResourceModel('reports/product_collection')
                            ->addAttributeToSelect('*')
                            ->addAttributeToFilter('status',1)
                            ->addOrderedQty()
                            ->setOrder('ordered_qty', 'desc')->setPage(1, 8);
            $outputHtml = '<div class="topbuy-home-block">
                            <h2>Recommendations</h2>
                            <div id="topbuy-home-recommendation">
                                <ul id="topbuy-rmd-carousel" class="topbuy-jcarousel-skin-home-rmd">';
            $index_id = 0;
            foreach ($productCollection as $product) {
                if (isset($product)) {
                    if(Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty()>0){
                        try {
                            $imageURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getSmallImage();
                            $productURL = $product->getProductUrl();
                            $salePrice = $_coreHelper->currency($product->getPrice(), true, false);
                            $productName = $product->getName();
                            $listPrice = $_coreHelper->currency($product->getListprice(), true, false);
                            $outputHtml = $outputHtml . ' 
                            <li>
                                <a href="' . $productURL . '" title="" target="_blank" >
                                    <img src="' . $imageURL . '" width="120" height="120" alt="" />
                                    <h4>' . $productName . '</h4>
                                    <p>' . $salePrice . '&nbsp;&nbsp;&nbsp;<span>RRP:<del>' . $listPrice . '</del></span></p>
                                </a>
                            </li>';
                            $index_id++;
                        } catch (Mage_Core_Exception $e) {

                        }
                    }
                }
            }
            $outputHtml = $outputHtml . '</ul></div></div>';
        }
        echo $outputHtml;
        die();
    }
    
    public function categorylvAction() {
        $_coreHelper = Mage::helper('core');
        $index_id = 0;
        $idproduct = Mage::getSingleton('core/cookie')->get("topbuy_cookie_customerr");
        $product = Mage::getModel('catalog/product')->load($idproduct);
        $idtbProduct = $product->getIdtbproduct();
        $productAvail = Mage::getModel('avail/productavail')->getCollection()->addFilter('idsource', $idtbProduct)
                ->addFilter('referType', 1)->addFilter('sourceType', 0)->setOrder('position','ASC');
        $productAvail->getSelect()->group('idrefer')->limit(10);
        
        if ($productAvail->getFirstItem()->hasData()&&$product->getStatus()==1) {
            $outputHtml = '<div class="topbuy-category-rmd">
                    <h3>Continue Shopping: <span>Customers Who Bought Items in Your Recent History Also Bought</span></h3>
                    <ul class="topbuy-category-rmd-carousel topbuy-jcarousel-skin-category-rmd">';

            foreach ($productAvail as $item) {
                $_product_id = $item->getIdrefer();
                if ($_product_id != "" && $_product_id != 0) {
                    try {
                        $product = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')
                                        ->addAttributeToFilter('idtbproduct', $_product_id)->getFirstItem();
//                        $product = Mage::getModel('catalog/product')->load($_product_id);

                        if ($product->hasData()&&Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty()>0) {
                            $imageURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getSmallImage();
                            $productURL = $product->getProductUrl();
                            $salePrice = $_coreHelper->currency($product->getPrice(), true, false);
                            $productName = $product->getName();
                            $listPrice = $_coreHelper->currency($product->getListprice(), true, false);
                            $outputHtml = $outputHtml . ' 
                            <li>
                                <a href="' . $productURL . '" title="" target="_blank" >
                                    <img src="' . $imageURL . '" width="120" height="120" alt="" />
                                    <h4>' . $productName . '</h4>
                                    <p>' . $salePrice . '&nbsp;&nbsp;&nbsp;<span>RRP:<del>' . $listPrice . '</del></span></p>
                                </a>
                            </li>';
                            $index_id++;
                        }
                    } catch (Mage_Core_Exception $e) {
                        
                    }
                }
            }

            $outputHtml = $outputHtml . '</ul>
                                        </div>
                                        <div class="clear"></div>
                                    ';
        } else if ($index_id <= 1) {
            //display default 8 products
//			$_helper = $this->helper('catalog/output');
            $productCollection = Mage::getResourceModel('reports/product_collection')
                            ->addAttributeToSelect('*')
                            ->addAttributeToFilter('status',1)
                            ->addOrderedQty()
                            ->setOrder('ordered_qty', 'desc')->setPage(1, 8);
            $outputHtml = '<div class="topbuy-category-rmd">
                            <h3>Continue Shopping: <span>Customers Who Bought Items in Your Recent History Also Bought</span></h3>
                            <ul class="topbuy-category-rmd-carousel topbuy-jcarousel-skin-category-rmd">';
            $index_id = 0;
            foreach ($productCollection as $product) {
                if (isset($product)) {
                    if(Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty()>0){
                        try {
                            $imageURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getSmallImage();
                            $productURL = $product->getProductUrl();
                            $salePrice = $_coreHelper->currency($product->getPrice(), true, false);
                            $productName = $product->getName();
                            $listPrice = $_coreHelper->currency($product->getListprice(), true, false);
                            $outputHtml = $outputHtml . ' 
                            <li>
                                <a href="' . $productURL . '" title="" target="_blank" >
                                    <img src="' . $imageURL . '" width="120" height="120" alt="" />
                                    <h4>' . $productName . '</h4>
                                    <p>' . $salePrice . '&nbsp;&nbsp;&nbsp;<span>RRP:<del>' . $listPrice . '</del></span></p>
                                </a>
                            </li>';
                            $index_id++;
                        } catch (Mage_Core_Exception $e) {

                        }
                    }
                }
            }
            $outputHtml = $outputHtml . '</ul>
                                            </div>
                                            <div class="clear"></div>
                                        ';
        }
        echo $outputHtml;
        die();
    }

}