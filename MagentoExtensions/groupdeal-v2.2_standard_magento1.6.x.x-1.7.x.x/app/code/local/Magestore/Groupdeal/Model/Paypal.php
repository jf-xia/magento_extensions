<?php

class Magestore_Groupdeal_Model_Paypal extends Mage_Paypal_Model_Api_Standard{
	public function getStandardCheckoutRequest()
    {			
        $request = $this->_exportToRequest($this->_commonRequestFields);
        $request['charset'] = 'utf-8';

        $isLineItems = $this->_exportLineItems($request);
        if ($isLineItems) {
            $request = array_merge($request, array(
                'cmd'    => '_cart',
                'upload' => 1,
            ));
            if (isset($request['tax'])) {
                $request['tax_cart'] = $request['tax'];
            }
            if (isset($request['discount_amount'])) {
                $request['discount_amount_cart'] = $request['discount_amount'];
            }
        } else {
            $request = array_merge($request, array(
                'cmd'           => '_ext-enter',
                'redirect_cmd'  => '_xclick',
            ));
        }

        // payer address
        $this->_importAddress($request);
        $this->_debug(array('request' => $request)); // TODO: this is not supposed to be called in getter
		
		//check item in order, if item is groupdeal product, paymentaction is authorization
		$order = Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());
		$items = $order->getAllItems();
		
		foreach($items as $item){
			$info = $item->getProductOptionByCode('info_buyRequest');			
			if($info){
				$groupdealProductId = $info['super_product_config']['product_id'];
				if(isset($info['product'])) $checkProductInDeal = Mage::helper('groupdeal')->checkProductInDeal($info['product']);
				else $checkProductInDeal = null;
				if(isset($groupdealProductId) && $groupdealProductId){
					$quantity = $item->getQtyOrdered();
					$deal = Mage::getModel('groupdeal/deal')->loadDealByProduct($groupdealProductId);
					if (!$deal) $deal = Mage::helper('groupdeal')->checkProductInDeal($groupdealProductId);
					if($deal->getBought() + $quantity < $deal->getMinimumPurchase()){ //if total bought not reach min purchase
						$request['paymentaction'] = 'authorization';
					}
					break;
				}elseif ($checkProductInDeal != false && count($info['super_attribute'])){
					$quantity = $item->getQtyOrdered();
					$deal = $checkProductInDeal;
					if($deal->getBought() + $quantity < $deal->getMinimumPurchase()){ //if total bought not reach min purchase
						$request['paymentaction'] = 'authorization';
					}
					break;
				}
			}
		}
        return $request;
    }
}