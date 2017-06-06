<?php

class Magestore_Groupdeal_Model_Observer {
	public function sales_order_save_after($observer){
		$order = $observer->getEvent()->getOrder();
		
		foreach ($order->getAllItems() as $item) {
			$info = $item->getProductOptionByCode('info_buyRequest');			
			if($info){
				$groupdealProductId = $info['super_product_config']['product_id'];				
				$productId = $info['product'];				
				$isGroupdealProduct = Mage::helper('groupdeal')->isGroupdealProduct($groupdealProductId);
				$countAttribute = count($info['super_attribute']);
				if(isset($groupdealProductId) && !$isGroupdealProduct){
					$checkProductInDeal = Mage::helper('groupdeal')->checkProductInDeal($groupdealProductId);	
					$countAttribute += 1;
				}else{
					$checkProductInDeal = Mage::helper('groupdeal')->checkProductInDeal($productId);				
				}
				
				if((isset($groupdealProductId) && $groupdealProductId && $isGroupdealProduct)
							||($checkProductInDeal != false && $countAttribute)){
							
					$transaction = Mage::getModel('sales/order_payment_transaction')->getCollection()
						->addFieldToFilter('order_id', $order->getId())
						->addFieldToFilter('is_closed', 0)
						//->addFieldToFilter('parent_txn_id', NULL)
						->addFieldToFilter('txn_type', array('in'=> array('authorization', 'capture')))
						->getFirstItem();
						
					if($transaction && $transaction->getId()){
						if(isset($groupdealProductId) && $groupdealProductId && $isGroupdealProduct) $deal = Mage::getModel('groupdeal/deal')->loadDealByProduct($groupdealProductId);
						else $deal = $checkProductInDeal;
						$quantity = $item->getQtyOrdered();
						
						try{
							$groupdealOrder = Mage::getModel('groupdeal/orderlist');
							$groupdealOrder->setDealId($deal->getDealId())
										->setOrderId($order->getId())
										->setQuantity($quantity)
										->save();
							
							$bought = $deal->getBought()+$quantity;
							
							
							if($deal->getBought() < $deal->getMinimumPurchase() && $bought >= $deal->getMinimumPurchase()){ //after buy, deal reach target
								$collection = Mage::getModel('groupdeal/orderlist')->getCollection()
											->addFieldToFilter('deal_id', $deal->getDealId())
											->addFieldToFilter('order_id', array('neq'=>$groupdealOrder->getOrderId()))
											->setOrder('orderlist_id', 'ASC');
											
								$captureUrl = Mage::helper('groupdeal')->getCaptureUrl($deal);
								$index = 0;
								foreach($collection as $item){
									Mage::helper('groupdeal')->doCapture($captureUrl, $item);//capture payment paypal
									Mage::helper('groupdeal/email')->sendOnDealEmailToCustomer($deal, $item);//send to on deal
								}
							}
							
							if($bought < $deal->getMinimumPurchase()){
								Mage::helper('groupdeal/email')->sendWaitingDealEmailWhenBuy($deal, $groupdealOrder);//send waiting deal
							}else{
								Mage::helper('groupdeal/email')->sendOnDealEmailWhenBuy($deal, $groupdealOrder);//send on deal
							}
							
							//add bought of deal, change deal status 
							$deal->setBought($bought)->save();
							$deal->setStatus();
							
						}catch(Exception $e){
						}
					}
				
					break;
				}
			}
		}		
	}
	
	public function checkout_cart_product_add_after($observer)
	{		
		$addedProduct = $observer->getProduct();
		$addedItem = $observer->getQuoteItem();
		
		$items = $this->_getCart()->getItems();
		if(Mage::helper('groupdeal')->isGroupdealProduct($addedProduct->getId())){
			foreach($items as $item){
				//check if this is a product in Deal
				$option = $item->getOptionByCode('product_type');
				if($option){
					$groupedProductId = $option->getProduct()->getId();	
					if($groupedProductId == $addedProduct->getId()){
						continue;
					}
				}
				//remove existed items
				$this->_getCart()->removeItem($item->getId());
			}
		} else {
			
			foreach($items as $item){
				//check if there is deal product in cart
				$product = $item->getProduct();
				$productId = $product->getId();					
				if(Mage::helper('groupdeal')->checkProductInDeal($productId) != false && 
						Mage::helper('groupdeal')->checkTypeProduct($product->getTypeId())){					
					$this->_getCart()->removeItem($item->getId());						
				}	
				
				$option = $item->getOptionByCode('product_type');				
				if($option){
					$groupedProductId = $option->getProduct()->getId();
					if(Mage::helper('groupdeal')->isGroupdealProduct($groupedProductId)){
						//remove recently added item
						$this->_getCart()->removeItem($addedItem->getId());
						Mage::throwException(Mage::helper('groupdeal')->__('Can\'t add more products to cart.'));
						return ;
					}elseif (Mage::helper('groupdeal')->checkProductInDeal($groupedProductId) != false){
						$this->_getCart()->removeItem($addedItem->getId());
						// Mage::throwException(Mage::helper('groupdeal')->__('Can\'t add more products to cart.'));
						return ;
					}
				}					
			}
		}
	}
	
	public function checkout_cart_update_items_after($observer)
	{
		if(Mage::registry('groupdeal_checkout_cart_update_items_after')){
			return;
		}
		
		Mage::register('groupdeal_checkout_cart_update_items_after',true);
		
		$itemInfo = $observer->getInfo();
		$items = $this->_getCart()->getItems();
		$max_qty = 0;
		if(count($items)){
			foreach($items as $item){
				$option = $item->getOptionByCode('product_type');
				if($option){
					$groupedProductId = $option->getProduct()->getId();	
					$checkProductInDeal = Mage::helper('groupdeal')->checkProductInDeal($groupedProductId);							
					if(!Mage::helper('groupdeal')->isGroupdealProduct($groupedProductId) && $checkProductInDeal == false){
						break;
					}
					//get max qty of deal items
					if(isset($itemInfo[$item->getId()])){
						if($itemInfo[$item->getId()]['qty'] > $max_qty){
							$max_qty = $itemInfo[$item->getId()]['qty']; 
						}
					}					
				}			
			}
		}
		
		//update qty of deal items
		if($max_qty){
			foreach ($itemInfo as $itemId => $info){
				$itemInfo[$itemId]['qty'] = $max_qty;
			}
			$this->_getCart()->updateItems($itemInfo);
		}
		
	}
	
	public function sales_quote_remove_item($observer)
	{
		$removedItem = $observer->getQuoteItem();
		$option = $removedItem->getOptionByCode('product_type');
		if(!$option){
			return $this;
		}
	
		$rm_groupedProductId = $option->getProduct()->getId();	
		$checkProductInDeal = Mage::helper('groupdeal')->checkProductInDeal($rm_groupedProductId);	
		if(!Mage::helper('groupdeal')->isGroupdealProduct($rm_groupedProductId) && $checkProductInDeal == false){
			return $this;
		}
		
		$items = $this->_getCart()->getItems();
		if(count($items)){
			foreach($items as $item){
				$option = $item->getOptionByCode('product_type');
				if(!$option){
					continue;
				}
				$groupedProductId = $option->getProduct()->getId();	
				//remove item if it is in Deal
				if($groupedProductId == $rm_groupedProductId){
					$this->_removeCartItem($item);					
				}
			}
		}
	}	
	
	// if product is groupdeal, redirect to groupdeal
	public function catalog_controller_product_init_after($observer){ 
		$product = $observer->getProduct();
		$productId = $product->getId();
		$controllers = $observer->getControllerAction();
		if(Mage::helper('groupdeal')->isGroupdealProduct($productId)){
			$deal = Mage::getModel('groupdeal/deal')->loadDealByProduct($productId);
			$controllers->getResponse()->setRedirect($deal->getDealUrl());
		}
		
		$nameAction = $controllers->getFullActionName();				
		
		if($nameAction == 'groupdeal_index_getProductOption' || $nameAction == 'groupdeal_index_buy'){
			return;
		}
		
		$deal = Mage::helper('groupdeal')->checkProductInDeal($productId);	
		if($deal != false && Mage::helper('groupdeal')->checkTypeProduct($product->getTypeId())){				
			if(Mage::getSingleton('core/session')->getcheckForPopup()==0){															
				$controllers->getResponse()->setRedirect($deal->getDealUrl());	
				Mage::getSingleton('core/session')->setcheckForPopup(0);
			}
		}else{			
			Mage::getSingleton('core/session')->setcheckForPopup(0);
		}
	}
	
	public function getItemIdByProduct($productId, $cart){
		$items = $cart->getItems();
		foreach($items as $item){
			if($item->getProduct()->getId() == $productId){
				return $item->getId();
			}
			/* $option = $item->getOptionByCode('product_type');
			if(!$option){
				if($item->getProduct()->getId() == $productId)
					return $item->getId();
			}else{
				$groupedProductId = $option->getProduct()->getId();
				if(!Mage::helper('groupdeal')->isGroupdealProduct($groupedProductId) &&  $item->getProduct()->getId() == $productId){
					return $item->getId();
				}else
					return NULL;
			} */
		}
	}
	
	public function catalog_product_get_final_price($observer){
		$product = $observer->getProduct();					
		Mage::getSingleton('core/session')->setForCheckout(0);	
		
		$cart = Mage::helper('checkout/cart')->getCart();
		
		$items = $cart->getItems();		
		if(!count($items))
			return;		
			
		$mainProductInCarts = $cart->getProductIds();			
		$count = count($items) - 1;				
		if($count == 0) $count = 1;
		$discount = Mage::getSingleton('core/session')->getSpecialPriceInDeal();
		
		$checkProductInDeal = Mage::helper('groupdeal')->checkProductInDeal($product->getId());				
		$checkProductHaveParentInDeal = Mage::helper('groupdeal')->checkProductForCheckout($product->getId(), $mainProductInCarts[0]);
		$checkProductHasOptions = Mage::helper('groupdeal')->checkTypeProduct($product->getTypeId());
		if(($checkProductInDeal != false && $checkProductHasOptions) 
				|| $checkProductHaveParentInDeal != false){			
			$deal = Mage::helper('groupdeal')->getDeal();
			if ($deal){			
				$finalPrice = $deal->getDealPrice();									
				$product->setFinalPrice($finalPrice/$count);	
				// Zend_debug::dump($finalPrice);
			}											
			if($discount){				
				$price = $finalPrice/$count;					
				$price = ($price*100)/$discount;			
				$product->setFinalPrice($price);								
			}
			return;
		}	
		
		foreach($items as $item){				
			$option = $item->getOptionByCode('product_type');	
			
			if($option){
				$groupedProductId = $option->getProduct()->getId();
				$checkProductInDeal = Mage::helper('groupdeal')->checkProductInDeal($groupedProductId);							
				if(Mage::helper('groupdeal')->isGroupdealProduct($groupedProductId) && ($product->getId() == $item->getProduct()->getId())){
					$deal = Mage::getModel('groupdeal/deal')->loadDealByProduct($groupedProductId);
					$product->setFinalPrice($deal->getDealPrice()/count($items));					
					return;
				}elseif ($checkProductInDeal != false){
					$product->setFinalPrice($checkProductInDeal->getDealPrice()/count($items));
					return;
				}
			 }									
		}
				
	}
		
	public function groupdeal_deal_delete_before($observer)
	{
		$deal = $observer->getDeal();
		if(Mage::registry('groupdeal_deal_delete_'.$deal->getId())){
			return $this;
		}
		
		Mage::register('groupdeal_deal_delete_'.$deal->getId(),true);
		
		$product = Mage::getModel('catalog/product')->load($deal->getProductId());
		if($product->getId()){
			$product->delete();
		}
	}
	
	public function catalog_product_delete_before($observer)
	{
		$product = $observer->getProduct();

		if(Mage::registry('groupdeal_product_delete_'.$product->getId())){
			return $this;
		}
		
		Mage::register('groupdeal_product_delete_'.$product->getId(),true);
		
		$deal = Mage::getResourceModel('groupdeal/deal_collection')
						->addFieldToFilter('product_id',$product->getId())
						->getFirstItem();
		if($deal->getId()){
			$deal->delete();
		}
	}
	
	protected function _getCart()
	{
		return Mage::getSingleton('checkout/cart');
	}
	
	protected function _removeCartItem($item)
	{
		$this->_getCart()->getQuote()->setIsMultiShipping(false);
		$item->isDeleted(true);
		if ($item->getHasChildren()) {
			foreach ($item->getChildren() as $child) {
				$child->isDeleted(true);
			}
		}			
	}		
	
	public function catalog_product_type_configurable_price($observer){
		$product = $observer->getProduct();
		//if product in deal -> setConfiurablePrice(0)
		if(Mage::helper('groupdeal')->checkProductInDeal($product->getId()) != false){	
			$product->setConfigurablePrice(0);
		}		
	}
	
	public function sales_quote_merge_before($observer){
		// $customerQuote = Mage::getModel('sales/quote')
            // ->setStoreId(Mage::app()->getStore()->getId())
            // ->loadByCustomer(Mage::getSingleton('customer/session')->getCustomerId());
		// Zend_debug::dump(count($observer->getAllVisibleItems()));
		$flag = false;
		$allItems = $observer->getSource()->getAllVisibleItems();//notlogin
		$all = $observer->getQuote()->getAllItems();//customer			
		foreach ($allItems as $item){
			$product = $item->getProduct();
			$option = $item->getOptionByCode('product_type');
			$groupedProductId = null;
			$checkGroup = false;
			if($option){
					$groupedProductId = $option->getProduct()->getId();						
					 $checkGroup = Mage::helper('groupdeal')->isGroupdealProduct($groupedProductId);
					 if(!$checkGroup)$checkGroup = Mage::helper('groupdeal')->checkProductInDeal($groupedProductId);
			}			
			if((Mage::helper('groupdeal')->checkProductInDeal($product->getId())) != false && (Mage::helper('groupdeal')->checkTypeProduct($product->getTypeId()))
						|| $checkGroup){
				$flag = true; 				
				break;
			}
			// Zend_debug::dump($item->getProduct()->getId());
		}
		if ($flag){
			foreach ($all as $item){
				 $observer->getQuote()->removeItem($item->getId());
			}
		}else{
			foreach ($all as $item){
				$product = $item->getProduct();
				$option = $item->getOptionByCode('product_type');
				$groupedProductId = null;
				$checkGroup = false;
				if($option){
					$groupedProductId = $option->getProduct()->getId();						
					 $checkGroup = Mage::helper('groupdeal')->isGroupdealProduct($groupedProductId);
					 if(!$checkGroup)$checkGroup = Mage::helper('groupdeal')->checkProductInDeal($groupedProductId);
				}	
				if((Mage::helper('groupdeal')->checkProductInDeal($product->getId())) != false && (Mage::helper('groupdeal')->checkTypeProduct($product->getTypeId()))
						|| $checkGroup){
					$observer->getQuote()->removeItem($item->getId());
				}
				
			}
		}				
	}
}