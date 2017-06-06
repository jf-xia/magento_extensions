<?php
    class Click2Customer_Analytics_Model_Page extends Varien_Object {
        private $madeCall = false;
        
        private function makeCall($pageInfo) {
            $this->madeCall = true;
            $config = Mage::getSingleton('analytics/config');
            $accountId = $config->getAccountId();
            $url = $config->getURL();
            $ordernum = 0;
            $cust_id = 0;
            $cust_group = '';
            $addto_cart='';
            $addto_cart_attributes='';
            $basket_subtotal='';
            $ref = '';
            if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
                $ref = urlencode( $_SERVER['HTTP_REFERER'] );
            }
            $basket_subtotal = 0.00;

            $visitor_id = $pageInfo['visitor_id'];
            if ( !empty( $pageInfo['cust_id'] ) ) {
                $cust_id = $pageInfo['cust_id'];
            }
            if ( isset( $pageInfo['basket_subtotal'] ) ) {
                $basket_subtotal = $pageInfo['basket_subtotal'];
            }

            $call = $url .'/content/site.php?aid=' .$accountId .'&ref=' .$ref .'&actioncode=' .$pageInfo['action_code'] 
                .'&ordernum=' .$ordernum .'&cust_id=' .$cust_id .'&customer_group=' .$cust_group .'&addto_cart=&addto_cart_attributes='
                .(isset( $pageInfo['item_code'] ) ? '&prodcode=' .$pageInfo['item_code'] : '' )
                .(isset( $pageInfo['cat_code'] ) ? '&catcode=' .$pageInfo['cat_code'] : '' )
                .'&basket_subtotal=' .$basket_subtotal .'&visitor_id=' .$visitor_id .'&pagecode=' .$pageInfo['page_code'] .'&ref_page=' .$ref .'&rulegroups=DSP,RWD,DSC,CUST';
            if ( isset( $_REQUEST['ssdebug'] ) ) {
                echo( '<pre style="color:red;">' );
                echo( $call );
                echo( '</pre>' );
            }
            $context = @stream_context_create(array(
                'http'=>array(
                    'timeout'=>10
                )
            ));
            $resp = unserialize( file_get_contents( $call, false, $context ) );
            
            $helper = Mage::helper('analytics/data');
            $helper->setResponse($resp);
        }
        
        
        public function getPageInfo() {
            $helper = Mage::helper('analytics/data');
            $page_code = '';
            $action_code = '';
            // get the current page
            $current_page = '';
            $cust_id = 0;
            $visitor_id = $_COOKIE['_ss_vid'];
            Mage::getSingleton('customer/session')->isLoggedIn();
            $session = Mage::getSingleton('customer/session');
            $cidData = $session->isLoggedIn();
            if ( $cidData === true ) {
                $cust_id = $session->getId();
            }
            
            /*
            * Check to see if its a CMS page
            * if it is then get the page identifier
            */
            if(Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms') {
                $current_page = Mage::getSingleton('cms/page')->getIdentifier();
            }
            /*
            * If its not CMS page, then just get the route name
            */
            if(empty($current_page)) {
                $current_page = Mage::app()->getFrontController()->getRequest()->getRouteName();
            }
            
            /*
            * What if its a catalog page?
            * Then we can get the category path :)
            */
            if($current_page == 'catalog') {
                $pathShort	= Mage::app()->getFrontController()->getRequest()->getModuleName() . "_" . Mage::app()->getFrontController()->getRequest()->getControllerName();
        		$pathFull	= $pathShort . "_" .Mage::app()->getFrontController()->getRequest()->getActionName();
        		if ( $pathFull === 'catalog_category_view' ) {
        		    $page_code  = 'CTGY';
        		    $layer = Mage::getSingleton('catalog/layer');
                    $_category = $layer->getCurrentCategory();
                    $cat_code= $_category->getId();
//                    $cat_code = preg_replace('#[^a-z0-9]+#', '-', strtolower(Mage::registry('current_category')->getUrlPath()));
                } else if ( $pathFull == 'catalog_product_view' ) {
                    $layer = Mage::getSingleton('catalog/layer');
                    $_category = $layer->getCurrentCategory();
                    $cat_code= $_category->getId();
                    $page_code = 'PROD';
                    $_product = $product = Mage::registry('current_product');
                    $item_code = $_product->getSku();
                }
            } else if ( $current_page == 'review' ) {
                $page_code = 'review';
                $_product = $product = Mage::registry('current_product');
                $item_code = $_product->getSku();
            } else if ( $current_page == 'checkout' ) {
                $pathShort	= Mage::app()->getFrontController()->getRequest()->getModuleName() . "_" . Mage::app()->getFrontController()->getRequest()->getControllerName();
        		$pathFull	= $pathShort . "_" .Mage::app()->getFrontController()->getRequest()->getActionName();
        		if ( $pathShort === 'checkout_cart' ) {
        		    $page_code = 'BASK';
        		    $action_code = Mage::app()->getFrontController()->getRequest()->getActionName();
        		    $cart = Mage::getSingleton('checkout/session')->getQuote();
		            $cartItems = $cart->getAllVisibleItems();
		            if ( !empty( $cartItems ) ) {
            		    $basket_items = array();
            		    $basket_qtys = array();
            		        
                        foreach( $cartItems as $cartItem ) {
                            array_push( $basket_items, $cartItem->getSku() );
                            array_push( $basket_qtys, $cartItem->getQty() );
                        }
                    }
        		} else if ( $pathFull == 'checkout_onepage_success' ) {
        		    $page_code = 'INVC';
        		}
            } else {
                $page_code = $current_page;
            }
            $totals = Mage::getSingleton('checkout/cart')->getQuote()->getTotals();
            if ( $totals ) {
                $basket_subtotal = $totals['subtotal'];
            }
            
            
            $pathShort	= Mage::app()->getFrontController()->getRequest()->getModuleName() . "_" . Mage::app()->getFrontController()->getRequest()->getControllerName();
    		$pathFull	= $pathShort . "_" .Mage::app()->getFrontController()->getRequest()->getActionName();

            
            if ( isset( $_GET['cat'] ) ) {
                $category = Mage::getModel('catalog/category')->load( $_GET['cat'] );
                $cat_code = preg_replace('#[^a-z0-9]+#', '-', strtolower( $category->getName() ) );
            }
            
            $retArr = array(
                'path_short'=>$pathShort,
                'path_full'=>$pathFull,
                'page_code'=>$page_code,
                'action_code'=>$action_code,
                'cust_id'=>$cust_id,
                'visitor_id'=>$visitor_id
            );
            if ( isset( $cat_code ) ) {
                $retArr['cat_code'] = $cat_code;
            }
            if ( isset( $item_code ) ) {
                $retArr['item_code'] = $item_code;
            }
            if ( isset( $basket_items ) ) {
                $retArr['basket_items'] = $basket_items;
            }
            if ( isset( $basket_qtys ) ) {
                $retArr['basket_qtys'] = $basket_qtys;
            }
            if ( isset( $basket_subtotal ) ) {
                $retAtt['basket_subtotal'] = $basket_subtotal;
            }
            
            if ( $this->madeCall === false ) {
                $this->makeCall($retArr);
            }
            
            return $retArr;
        }
    }
