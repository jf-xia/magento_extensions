<?php //

class Topbuy_Ajax_AvailController extends Mage_Core_Controller_Front_Action {

//    const post_url = "http://service.sg.avail.net/2009-02-13/dynamic/72e66652-f56b-11e0-9cab-12313b0349b4/services/jsonrpc-1.x/";
//
//    public function IndexAction() {
//		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
//			if ($ip == "")
//			{
//				$ip = $_SERVER["REMOTE_ADDR"];
//				}
//	 
//		echo $ip;
//        echo "You are on Avail function ";
//    }
//
//    public function LogclickedonAction() {
//        $request_action = (int) $this->getRequest()->getParam('request_action');
//        $idproduct = (string) $this->getRequest()->getParam('idproduct');
//        $emark_tracking_code = (string) $this->getRequest()->getParam('emark_tracking_code');
//        $emark_cookie = (string) $this->getRequest()->getParam('emark_cookie');
//        $result = "";
//
//        $request_string = "{\"method\":\"logClickedOn\",\"params\":{\"SessionID\":\"" . $emark_cookie . "\",\"TrackingCode\":\"" . $emark_tracking_code . "\",\"ProductID\":\"" . $idproduct . "\"},\"version\":\"1.1\"}";
//
//        if ($emark_cookie != "" && $emark_tracking_code != "" && $idproduct != "") {
//            //start to call avail to log click on 
//            $ch = curl_init(self::post_url);
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                'Content-Type: application/json-rpc',
//                'Content-Length: ' . strlen($request_string))
//            );
//            $result = curl_exec($ch);
//        }
//        echo $result;
//    }
//
//    public function LastviewdfooterAction() {
//        $request_action = (int) $this->getRequest()->getParam('request_action');
//        $idproduct = (string) $this->getRequest()->getParam('idproduct');
//        $emark_cookie = (string) $this->getRequest()->getParam('emark_cookie');
//        $emark_tracking_code = (string) $this->getRequest()->getParam('emark_tracking_code');
//        $productString = (string) $this->getRequest()->getParam('productString');
//        $products = explode(",", $productString);
//
//        $_coreHelper = Mage::helper('core');
//        $index_id = 0;
//        if (sizeof($products) > 0) {
//            $outputHtml = '<div class="topbuy-category-rmd">
//							<h3>Continue Shopping: <span>Customers Who Bought Items in Your Recent History Also Bought</span></h3>
//        						<ul class="topbuy-category-rmd-carousel topbuy-jcarousel-skin-category-rmd">';
//
//            foreach ($products as $_product_id) {
//                if ($_product_id != "" && $_product_id != 0) {
//                    try {
//                        $product = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')
//                                        ->addAttributeToFilter('idtbproduct', $_product_id)->getFirstItem();
//
//                        if ($product->hasData()) {
//                            $imageURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getSmallImage();
//                            $productURL = $product->getProductUrl() . "?emark_tracking_code=" . $emark_tracking_code;
//                            $salePrice = $_coreHelper->currency($product->getPrice(), true, false);
//                            $productName = $product->getName();
//                            $listPrice = $_coreHelper->currency($product->getListprice(), true, false);
//                            $outputHtml = $outputHtml . ' 
//										   <li>
//													<a href="' . $productURL . '" title="" target="_blank" >
//														<img src="' . $imageURL . '" width="120" height="120" alt="" />
//														<h4>' . $productName . '</h4>
//														<p>' . $salePrice . '&nbsp;&nbsp;&nbsp;<span>RRP:<del>' . $listPrice . '</del></span></p>
//													</a>
//										   </li>';
//                            $index_id++;
//                        }
//                    } catch (Mage_Core_Exception $e) {
//                        
//                    }
//                }
//            }
//
//            $outputHtml = $outputHtml . '           </ul>
//											</div>
//											<div class="clear"></div>
//										 ';
//        }
//        if ($index_id <= 1) {
//            //display default 8 products
////			$_helper = $this->helper('catalog/output');
//            $productCollection = Mage::getResourceModel('reports/product_collection')
//                            ->addAttributeToSelect('*')
//                            ->addOrderedQty()
//                            ->setOrder('ordered_qty', 'desc')->setPage(1, 8);
//            $outputHtml = '<div class="topbuy-category-rmd">
//							<h3>Continue Shopping: <span>Customers Who Bought Items in Your Recent History Also Bought</span></h3>
//        						<ul class="topbuy-category-rmd-carousel topbuy-jcarousel-skin-category-rmd">';
//            $index_id = 0;
//            foreach ($productCollection as $product) {
//                if (isset($product)) {
//                    try {
//
//                        $imageURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getSmallImage();
//                        $productURL = $product->getProductUrl() . "?emark_tracking_code=" . $emark_tracking_code;
//                        $salePrice = $_coreHelper->currency($product->getPrice(), true, false);
//                        $productName = $product->getName();
//                        $listPrice = $_coreHelper->currency($product->getListprice(), true, false);
//                        $outputHtml = $outputHtml . ' 
//										   <li>
//												<a href="' . $productURL . '" title="" target="_blank" >
//													<img src="' . $imageURL . '" width="80" height="120" alt="" />
//													<h4>' . $productName . '</h4>
//													<p>' . $salePrice . '&nbsp;&nbsp;&nbsp;<span>RRP:<del>' . $listPrice . '</del></span></p>
//												</a>
//										   </li>';
//                        $index_id++;
//                    } catch (Mage_Core_Exception $e) {
//                        
//                    }
//                }
//            }
//            $outputHtml = $outputHtml . '           </ul>
//											</div>
//											<div class="clear"></div>
//										 ';
//        }
//        echo $outputHtml;
//    }
//
//    public function LogremovedfromcartAction() {
//        $request_action = (int) $this->getRequest()->getParam('request_action');
//        $idproduct = (string) $this->getRequest()->getParam('idproduct');
//
//        $emark_cookie = (string) $this->getRequest()->getParam('emark_cookie');
//        $result = "";
//        $request_string = "{\"method\":\"logRemovedFromCart\",\"params\":{\"SessionID\":\"" . $emark_cookie . "\",\"ProductID\":\"" . $idproduct . "\"},\"version\":\"1.1\"}";
//        if ($emark_cookie != "" && $idproduct != "") {
//            //start to call avail to log click on 
//            $ch = curl_init(self::post_url);
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                'Content-Type: application/json-rpc',
//                'Content-Length: ' . strlen($request_string))
//            );
//            $result = curl_exec($ch);
//        }
//        echo $result;
//    }
//
//    public function LogpurchaseAction() {
//        $request_action = (int) $this->getRequest()->getParam('request_action');
//        $idorder = (string) $this->getRequest()->getParam('idorder');
//        $emark_cookie = (string) $this->getRequest()->getParam('emark_cookie');
//        $result = "";
//        $request_string = "";
//        if ($emark_cookie != "" && $idorder != "") {
//            //start to call avail to log click on 
//            //retrieve idcustomer
//            $order = Mage::getModel('sales/order')->loadByIncrementId($idorder);
//            if (isset($order)) {
//                $total = $order->base_grand_total;
//                if ($total > 0) {
//                    $productString = "";
//                    $priceString = "";
//                    $customer = $order->getCustomer();
//                    $customer = Mage::getModel('customer/customer')->load($order->customer_id);
//                    $idcustomer = Mage::getModel('homepage/customermap')->getCollection()->addFilter('id_magcustomer', $customer->getId())->getFirstItem()->getIdTbcustomer();
//
//                    $orderItems = $order->getAllItems();
//                    foreach ($orderItems as $item) {
//                        $price = sprintf("%01.2f", $item->price);
//                        $idproduct = $item->product_id;
//                        if ($price > 0) {
//                            $product = Mage::getModel('catalog/product')->load($idproduct);
//                            $idtbproduct = $product->getIdtbproduct();
//                            if ($idtbproduct > 0) {
//                                if ($productString == "") {
//                                    $productString = "\"" . $idtbproduct . "\"";
//                                    $priceString = "\"" . $price . "\"";
//                                } else {
//                                    $productString = $productString . ",\"" . $idtbproduct . "\"";
//                                    $priceString = $priceString . ",\"" . $price . "\"";
//                                }
//                            }
//                        }
//                    }
//                    if ($productString != "" && $priceString != "") {
//                        /*
//                          echo $emark_cookie."******<br/>";
//                          echo $productString."******<br/>";
//                          echo $priceString."******<br/>";
//                          echo $idorder."******<br/>";
//                          echo $idcustomer."******<br/>";
//                         */
//                        $request_string = "{\"method\":\"logPurchase\",\"params\":{\"SessionID\":\"" . $emark_cookie . "\",\"UserID\":\"" . $idcustomer . "\",\"ProductIDs\":[" . $productString . "],\"Prices\":[" . $priceString . "],\"OrderID\":\"" . $idorder . "\"},\"version\":\"1.1\"}";
//                        //echo $request_string."******<br/>";
//                    }
//                }
//            }
//
//            if ($request_string != "") {
//
//                $ch = curl_init(self::post_url);
//                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//                curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                    'Content-Type: application/json-rpc',
//                    'Content-Length: ' . strlen($request_string))
//                );
//                $result = curl_exec($ch);
//            }
//
//            //	$idcustomer = $customer->getId();
//            //	echo $idcustomer;
//            //retrieve idproduct with price, change price = 0 to 0.1
//            //check order total only pass order amount more than 0 
//
//            /*
//
//             */
//        }
//        echo $result;
//    }
//
//    public function LogaddedtocartAction() {
//        $request_action = (int) $this->getRequest()->getParam('request_action');
//        $idproduct = (string) $this->getRequest()->getParam('idproduct');
//        $emark_tracking_code = (string) $this->getRequest()->getParam('emark_tracking_code');
//        $emark_cookie = (string) $this->getRequest()->getParam('emark_cookie');
//        $result = "";
//
//        if ($emark_tracking_code != "") {
//            $request_string = "{\"method\":\"logAddedToCart\",\"params\":{\"SessionID\":\"" . $emark_cookie . "\",\"TrackingCode\":\"" . $emark_tracking_code . "\",\"ProductID\":\"" . $idproduct . "\"},\"version\":\"1.1\"}";
//        } else {
//            $request_string = "{\"method\":\"logAddedToCart\",\"params\":{\"SessionID\":\"" . $emark_cookie . "\",\"ProductID\":\"" . $idproduct . "\"},\"version\":\"1.1\"}";
//        }
//
//
//        if ($emark_cookie != "" && $idproduct != "") {
//            //start to call avail to log click on 
//            $ch = curl_init(self::post_url);
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                'Content-Type: application/json-rpc',
//                'Content-Length: ' . strlen($request_string))
//            );
//            $result = curl_exec($ch);
//        }
//        echo $result;
//    }
//
//    public function ProductpageAction() {
//        $request_action = (int) $this->getRequest()->getParam('request_action');
//        $idproduct = (string) $this->getRequest()->getParam('idproduct');
//        $emark_cookie = (string) $this->getRequest()->getParam('emark_cookie');
//        $emark_tracking_code = (string) $this->getRequest()->getParam('emark_tracking_code');
//        $productString = (string) $this->getRequest()->getParam('productString');
//        $products = explode(",", $productString);
//        $_coreHelper = Mage::helper('core');
//        if (sizeof($products) > 0) {
//            $outputHtml = '<div id="topbuy-avail-productpage"> 
//							<div id="topbuy-prd-right-rmd">
//									  <h2 class="topbuy-prd-blocktitle">you May Also Like</h2>
//									  <ul class="topbuy-prd-right-rmd-carousel topbuy-jcarousel-skin-prd-right-rmd">';
//            $index_id = 0;
//            foreach ($products as $_product_id) {
//                if ($_product_id != "" && $_product_id != 0) {
//                    try {
//                        $product = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')
//                                        ->addAttributeToFilter('idtbproduct', $_product_id)->getFirstItem();
//
//                        if ($product->hasData()) {
//                            $imageURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getSmallImage();
//                            $productURL = $product->getProductUrl() . "?emark_tracking_code=" . $emark_tracking_code;
//                            $salePrice = $_coreHelper->currency($product->getPrice(), true, false);
//                            $productName = $product->getName();
//                            if ($index_id % 2 == 0) {
//                                $outputHtml = $outputHtml . '<li>';
//                            }
//                            $outputHtml = $outputHtml . ' 
//										   <div class="topbuy-prd-right-rmd-cell">
//													<a href="' . $productURL . '" title="" target="_blank" >
//														<img src="' . $imageURL . '" width="80" height="80" alt="" />
//														<p>' . $productName . '</p>
//														<h4>' . $salePrice . '</h4>
//													</a>
//										   </div>';
//                            $index_id++;
//
//                            if (($index_id) % 2 == 0) {
//                                $outputHtml = $outputHtml . '<div class="clear"></div></li>';
//                            }
//                        }
//                    } catch (Mage_Core_Exception $e) {
//                        
//                    }
//                }
//            }
//            if (($index_id) % 2 != 0) {
//                $outputHtml = $outputHtml . '<div class="clear"></div></li>';
//            }
//
//            $outputHtml = $outputHtml . '           </ul>
//											</div>
//											<div class="clear"></div>
//										</div>	';
//        }
//        echo $outputHtml;
//    }

}
