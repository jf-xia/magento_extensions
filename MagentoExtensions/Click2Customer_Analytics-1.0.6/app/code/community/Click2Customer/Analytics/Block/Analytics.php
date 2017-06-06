<?php
    class Click2Customer_Analytics_Block_Analytics extends Mage_Core_Block_Text {
        protected function _loadCache() {
            return false;
        }

        protected function _saveCache($data) {
            return $this;
        }
        
        function _toHtml() {
            $config = Mage::getSingleton('analytics/config');
            $accountId = $config->getAccountId();
            $pageInfo = Mage::getSingleton('analytics/page')->getPageInfo();

            if ( $pageInfo['path_full'] === 'checkout_onepage_success' ) {
                $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
                $orderItems = $order->getAllItems();
                $ordItems = array();
                $ordPrices = array();
                $ordQtys = array();
                foreach( $orderItems as $ordItem ) {
                    $parentItem = $ordItem->getParentItem();
                    if ( is_null( $parentItem ) ) {
                        array_push($ordItems, $ordItem->getSku());
                        array_push($ordQtys, $ordItem->getQtyOrdered() );
                        $price = 0.00;
                        if( $this->_isProductCalculated($ordItem) === false ) {
                            $price = ($ordItem->getCustomPrice()!==null) ? $ordItem->getCustomPrice() : $ordItem->getPrice();
                            $discount = 0.00;
                            if ( $ordItem->getDiscountAmount() !== 0 ) {
                                $discount = ( $ordItem->getDiscountAmount() / $ordItem->getQtyOrdered() );
                            }
			                $price = ($price -  $discount );
                        } else {
                            $children = $ordItem->getChildren();
                            $options_subtotal = 0.00;
			                if ( count( $children ) > 0 ) {
			                    foreach($children as $child) {
			                        $childPrice = ($child->getCustomPrice()!==null) ? $child->getCustomPrice() : $child->getPrice();
                                    $childDiscount = 0.00;
                                    if ( $child->getDiscountAmount() !== 0 ) {
                                        $childDiscount = ( $child->getDiscountAmount() / $child->getQtyOrdered() );
                                    }
			                        $childPrice = ($childPrice -  $childDiscount );
                            		$childTaxData = $childTax->getData();
                            		$childSku = $child->getSku();
				                    $line['options'][$childSku] = array(
				                        'sku'=>$child->getSku(),
				                        'tax_class'=>$childTaxData['class_name'],
				                        'name'=>$child->getName(),
				                        'price'=>(float)$childPrice,
				                        'qty'=>$child->getQtyOrdered()
				                    );
				                    $options_subtotal += ( ( $childPrice * $child->getQtyOrdered() ));
			                    }
			                }
			                $price = $price + $options_subtotal;
                        }
                        
                        array_push( $ordPrices, (float)$price );
                    }
                }
            }
            
            $html = '';
            $html .= '<script id="c2c_config" type="text/javascript">' ."\n";
            $html .= "
                _ss_aid = '$accountId';
                _ss_visitor_id = '" .$pageInfo['visitor_id'] ."';
                _ss_track_flash = true; 
                _ss_search_var = 'q';                _ss_page_group = '" .$pageInfo['page_code'] ."';
                _ss_site_user = '" .$pageInfo['cust_id'] ."';                _ss_action_code = '" .$pageInfo['action_code'] ."';" ."\n"
                .(isset( $pageInfo['cat_code'] ) ? "_ss_category='" .$pageInfo['cat_code'] ."';" : "" )
                .(isset( $pageInfo['item_code'] ) ? "_ss_item_code='" .$pageInfo['item_code'] ."';" : "" )
                .(isset( $pageInfo['basket_items'] ) ? "_ss_basket_items='" .join( ';', $pageInfo['basket_items'] ) ."';" : "")
                .(isset( $pageInfo['basket_qtys'] ) ? "_ss_basket_qtys='" .join( ';', $pageInfo['basket_qtys'] ) ."';" : "");
            if ( isset( $order ) ) {
                $html .= '_ss_con=1;' ."\n";
                $html .= "_ss_ordernum='" .$order->getIncrementId() ."';" ."\n";
                $html .= "_ss_order_total='" .$order->getGrandTotal() ."';" ."\n";
                $html .= "_ss_items='" .join( ',', $ordItems ) ."';" ."\n";
                $html .= "_ss_qtys='" .join( ',', $ordQtys ) ."';" ."\n";
                $html .= "_ss_prices='" .join( ',', $ordPrices ) ."';" ."\n";
                $billing = $order->getBillingAddress();
                $shipping = $order->getShippingAddress();
                $payment = $order->getPayment();
                $html .= "_ss_billto_address1='" .$billing->getStreet(1) ."';" ."\n";
                $html .= "_ss_billto_address2='" .$billing->getStreet(2) ."';" ."\n";
                $html .= "_ss_billto_city='" .$billing->getCity() ."';" ."\n";
                $html .= "_ss_billto_state='" .$billing->getRegionCode() ."';" ."\n";
                $html .= "_ss_billto_zip='" .$billing->getPostcode() ."';" ."\n";
                $html .= "_ss_billing_email='" .$order->getCustomerEmail() ."';" ."\n";
                if( $payment->getMethod() ) {
                    $html .= "_ss_cc_name='" .$billing->getName() ."';" ."\n";
                    $html .= "_ss_cc_last4='" .$payment->getCcLast4() ."';" ."\n";
                    $html .= "_ss_payment_method='" .$payment->getCcType() ."';" ."\n";
                }
                
                $html .= "_ss_ship_method='" .$order->getShippingMethod() ."';" ."\n";
                $html .= "_ss_ship_amount='" .(float)$order->getShippingAmount() ."';" ."\n";
                $html .= "_ss_shipto_address1='" .$shipping->getStreet(1) ."';" ."\n";
                $html .= "_ss_shipto_address2='" .$shipping->getStreet(2) ."';" ."\n";
                $html .= "_ss_shipto_city='" .$shipping->getCity() ."';" ."\n";
                $html .= "_ss_shipto_state='" .$shipping->getRegionCode() ."';" ."\n";
                $html .= "_ss_shipto_zip='" .$shipping->getPostcode() ."';" ."\n";
                $html .= "_ss_shipping_email='" .$order->getCustomerEmail() ."';" ."\n";
                $tax = (float)( $order->getTaxAmount() ? $order->getTaxAmount() : 0.00);
                $charges = array();
                $charges_list = array();
                if ( $order->getShippingAmount() > 0 ) {
                    $charges['shipping'] = $order->getShippingAmount();
                }
                if ( $tax > 0 ) {
                    $charges['tax'] = $tax;
                }
                
                if ( !empty( $charges ) ) {
                    foreach( $charges as $charge=>$price ) {
                        array_push( $charges_list, "$charge,$price,$price" );
                    }
                }
                $html .= "_ss_charges='" .implode( '|', $charges_list ) ."';" ."\n";
            }
            $html .= '</script>' ."\n";
            $html .= '<div style="display:hidden;width:0px;height:0px;" id="ss_analytics"></div>' ."\n";
            return $html;
        }
        
        function _isProductCalculated($item) {
		    try {
			    if($item->isChildrenCalculated() && !$item->getParentItem()) {
				    return true;
			    }
			    if(!$item->isChildrenCalculated() && $item->getParentItem()) {
				    return true;
			    }
		    } catch(Exception $e) { }
		    return false;
	    }
    }
