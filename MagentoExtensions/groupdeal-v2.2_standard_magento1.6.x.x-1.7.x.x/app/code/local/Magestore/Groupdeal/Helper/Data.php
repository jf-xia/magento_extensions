<?php

class Magestore_Groupdeal_Helper_Data extends Mage_Core_Helper_Abstract{
	const WAITING 	= 6;
	const OPENING 	= 5;
	const REACHED 	= 4;
	const UNREACHED	= 3;
	const END 		= 2;
	const ENABLED 	= 1;
	const DISABLED	= 0;
	
	public function getGroupDealUrl(){
		return $this->_getUrl('groupdeal');
	}
	
	public function getTargetNotMetLabel(){
		return Mage::getStoreConfig('groupdeal/general/target_not_met');
	}
	
	public function getTargetMeetLabel(){
		return Mage::getStoreConfig('groupdeal/general/target_meet');
	}
	
	public function getSubscriberId($email){
		$subscriber = Mage::getModel('groupdeal/subscriber')->getCollection()
						->addFieldToFilter('email', $email)->getFirstItem();
		if($subscriber && $subscriber->getId())
			return $subscriber->getId();
		else
			return NULL;
	}
	
	public function createRewriteUrl($deal){
		$dealId = $deal->getId();
		$urlrewrite = Mage::getModel('groupdeal/urlrewrite')->load('groupdeal/'.$dealId, 'id_path');
		
		$urlrewrite->setData('id_path','groupdeal/'.$dealId);
		$urlrewrite->setData('request_path', 'groupdeal/' . $deal->getUrlKey());
	
		$urlrewrite->setData('target_path','groupdeal/index/deal/id/'. $dealId );
		$urlrewrite->setData('product_id', $deal->getProductId());
		
		try{
		
			$urlrewrite->save();				
		} catch (Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());	
		}
	}
	
	public function getGroupdealProductIds(){
		$attrSetName = 'Groupdeal';
		$attributeSetId = Mage::getModel('eav/entity_attribute_set')
    		->load($attrSetName, 'attribute_set_name')
    		->getAttributeSetId();

		$collection = Mage::getModel('catalog/product')->getCollection()
				->addFieldToFilter('attribute_set_id', $attributeSetId);
		
		
		$productIds = array();
		foreach ($collection as $item){
			$productIds[] = $item->getId();
		}
		return $productIds;
	}
	
	public function getGroupdealOrderIds($dealId){
		$collection = Mage::getModel('groupdeal/orderlist')->getCollection()
					->addFieldToFilter('deal_id', $dealId);
		$orderIds = array();
		foreach ($collection as $item){
			$orderIds[] = $item->getOrderId();
		}
		return $orderIds;
	}
	
	public function getActiveDeals(){
		return Mage::getModel('groupdeal/deal')->getCollection()
					->addFieldToFilter('deal_status', array('in'=> array(self::OPENING, self::REACHED)));
	}
	
	public function getActiveDealIds(){
		$collection = $this->getActiveDeals();
		$dealIds = array();
		foreach($collection as $item){
			$dealIds[] = $item->getId();
		}
		return $dealIds;
	}
	
	public function getActiveGroupdealProductIds($categoryId){
		$category = Mage::getModel('catalog/category')->load($categoryId);
		$dealIds = $this->getDealIdsInCategory($category);
		
		$productIds = array();
		
		foreach($dealIds as $dealId){
			$deal = Mage::getModel('groupdeal/deal')->load($dealId);
			$productIds[] = $deal->getProductId();
		}
		
		return $productIds;
	}
	
	public function getDealIdsInCategory($category){
		$products = $category->getProductCollection();
		$productIds = array();
		foreach($products as $product){
			$productIds[] = $product->getId();
		}
		
		$activeDealIds = $this->getActiveDealIds();
		$collection = Mage::getModel('groupdeal/productlist')->getCollection()
					->addFieldToFilter('product_id', array('in' => $productIds))
					->addFieldToFilter('deal_id', array('in' => $activeDealIds));
		
		$dealIds = array();
		foreach($collection as $item){
			$dealIds[] = $item->getDealId(); 
		}
		
		return $dealIds;
	}
	
	public function getWaitingGroupdealProductIds(){
		
		$collection = Mage::getModel('groupdeal/deal')->getCollection()
					->addFieldToFilter('deal_status', self::WAITING);
		
		$productIds = array();
		foreach($collection as $item){
			$productIds[] = $item->getProductId();
		}
		return $productIds;
	}
	
	public function getClosedGroupdealProductIds(){
		$collection = Mage::getModel('groupdeal/deal')->getCollection()
					->addFieldToFilter('deal_id',  array('in'=> array(self::END, self::UNREACHED)));
		
		$productIds = array();
		foreach($collection as $item){
			$productIds[] = $item->getProductId();
		}
		return $productIds;
	}
	
	
	public function getSendMailUnreachedDeals(){
		$collection = Mage::getModel('groupdeal/deal')->getCollection()
					->addFieldToFilter('deal_status', 3)
					->addFieldToFilter('is_sendmail_unreached', 0);
		return $collection;
	}
	
	
	public function createGroupdealProduct($name,$short_description, $full_description, $imageUrls, $specicalPrice, $regularPrice, $status, $isFeatured, $endTime, $urlKey, $productlist, $productId){
		if($productId){
			$product  =  Mage::getModel('catalog/product')->load($productId);
			$product->setTypeId('grouped');
			$product->setGroupedLinkData($productlist);
			$product->setGroupdealFeatured($isFeatured);
			$product->setGroupdealEndtime($endTime);
			
		}else{
			$product = Mage::getModel('catalog/product');
			$attributeSetName = 'Groupdeal';
			$entityType = Mage::getSingleton('eav/entity_type')->loadByCode('catalog_product');
			$entityTypeId = $entityType->getId();
			$setId = Mage::getResourceModel('catalog/setup', 'core_setup')->getAttributeSetId($entityTypeId, $attributeSetName);
			$product->setAttributeSetId($setId);
			$product->setTypeId('grouped');
			$product->setGroupedLinkData($productlist);
			//$product->setSku('gd_' . str_replace(' ', '', $name));
			$product->setWebsiteIDs(array(1)); 
			//$product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
			$product->setTaxClassId(0);
			$product->setStockData(array(
				'is_in_stock' => 1,
				'qty' => 99999
			));
			$product->setCreatedAt(now());
		}
		
		$product->setName($name);
		$product->setShortDescription($short_description);
		$product->setDescription($full_description);
		$product->setStatus($status);
		$product->setGroupdealFeatured($isFeatured);
		$product->setGroupdealEndtime($endTime);
		$this->addImagesToProduct($product, $imageUrls);	
		
		try{
			$product->save();
			return $product;
		}catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return; 
		}
	}
	
	protected function addImagesToProduct($product, $imageUrls){
		$attributes = $product->getTypeInstance(true)->getSetAttributes($product);
		$gallery = $attributes['media_gallery'];

		// remove all images
		$galleryData = $product->getMediaGallery();
		foreach($galleryData['images'] as $image){
			//If image exists
    		if ($gallery->getBackend()->getImage($product, $image['file'])) {
            	$gallery->getBackend()->removeImage($product, $image['file']);
        	}
		}
		
		$i = 0;
		foreach($imageUrls as $index => $imageUrl){
		if($imageUrl) {
			if($i == 0)
				$visibility = array('thumbnail', 'small_image', 'image');
			else
				$visibility = array();
			
			$fileName = Mage::getBaseDir('media') . DS . $imageUrl;		
			$file = $gallery->getBackend()->addImage($product, $fileName, $visibility, false, false);	
			//$gallery->getBackend()->updateImage($product, $file, array());
			}
			$i++;
		}
		$product->save();
	}

	
	public function assignProductIdsToDeal($deal, $productIds) {
		$added_products = 0;
		$productlistModel = Mage::getModel('groupdeal/productlist');
		if($productIds == array(0)) {
			return $this;
		}
		if(!count($productIds)) {
			$productIds = array(0);
		}
		
		try{
			foreach($productIds as $key => $productId) {
				$productId = (int) $productId;
				if($productId) {
					$productlistModel->loadProductlist($deal->getId(), $productId);
					if(!$productlistModel->getId())
						$added_products++;
					$productlistModel->setDealId($deal->getId());
					$productlistModel->setProductId($productId);
					$productlistModel->save();
					$productlistModel->setId(null);
				} else {
					unset($productIds[$key]);
				}
			}
			
			if(!count($productIds)) {
				$productIds = array(0);
			}
			
			$collection = Mage::getResourceModel('groupdeal/productlist_collection')
				->addFieldToFilter('product_id', array('nin'=>$productIds))
				->addFieldToFilter('deal_id', $deal->getId())
				;
				
			if(count($collection)) {
				foreach($collection as $item) {
					$item->delete();
					$added_referrals--;
				}
			}
		}catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}		
		return $this;
	}

	public function isGroupdealProduct($productId){
		if (!$productId) return false;
		
		$collection = Mage::getModel('groupdeal/deal')->getCollection()
				->addFieldToFilter('product_id', $productId);
		if($collection->getSize())
			return true;
		else
			return false;
	}
	
	public function getGroupdealProductIdFromItem($item){
		$option = $item->getOptionByCode('product_type');
		if($option){
			$groupedProductId = $option->getProduct()->getId();
			if($this->isGroupdealProduct($groupedProductId)){
				return groupedProductId;
			}
			return NULL;
		}
		return NULL;
	}
	
	public function sendCancelDealEmailToCustomers($deal){
		$collection = Mage::getModel('groupdeal/orderlist')->getCollection()
						->addFieldToFilter('deal_id', $deal->getDealId());
						
		$voidUrl = $this->getVoidUrl();
		try{
			$deal->setIsSendmailUnreached(1)->save();
			foreach($collection as $item){
				$this->doVoid($voidUrl, $item);//void payment paypal
				Mage::helper('groupdeal/email')->sendCancelDealEmailToCustomer($deal, $item);//send to on deal
			}
		}catch(Exception $e){
		}
	}
	
	public function doCapture($captureUrl, $groupdealOrder){
		$order = Mage::getModel('sales/order')->load($groupdealOrder->getOrderId());
		$payment = $order->getPayment();
		$captureUrl = $captureUrl . '&AUTHORIZATIONID=' . $payment->getLastTransId(). 
									'&CURRENCYCODE=' . $order->getBaseCurrencyCode() . 
									'&AMT=' . round($order->getGrandTotal(), 2);

		try{
			$http = new Varien_Http_Adapter_Curl();
			$http->setConfig($config);
			$http->write(Zend_Http_Client::GET, $captureUrl);
			$response = $http->read();
		}catch(Exception $e){
			
		}
	}
	
	
	public function doVoid($voidUrl, $groupdealOrder){
		$order = Mage::getModel('sales/order')->load($groupdealOrder->getOrderId());
		$payment = $order->getPayment();
		$voidUrl = $voidUrl . '&AUTHORIZATIONID=' . $payment->getLastTransId();
		//print_r($voidUrl);
		try{
			$http = new Varien_Http_Adapter_Curl();
			$http->write(Zend_Http_Client::GET, $voidUrl);
			$response = $http->read();
			
			$order->cancel()->save();
		}catch(Exception $e){
			
		}
	}
	
	public function getCaptureUrl(){
		$paypalApi = $this->getPaypalApi();
		$url = $this->getApiEndpoint();
		$url .= '&METHOD=DoCapture&COMPLETETYPE=COMPLETE';
		return $url;
	}
	
	public function getVoidUrl(){
		$url = $this->getApiEndpoint();
		$url .= '&METHOD=DoVoid';
		return $url;
	}
	
	public function getApiEndpoint(){
		$isSandbox = Mage::getStoreConfig('paypal/wpp/sandbox_flag');
		$paypalApi = $this->getPaypalApi();
        $url = sprintf('https://api-3t%s.paypal.com/nvp?', $isSandbox ? '.sandbox' : '');
		$url .= 'USER=' . $paypalApi['api_username'] . '&PWD=' . $paypalApi['api_password'] . '&SIGNATURE=' . $paypalApi['api_signature']
			 . '&VERSION=62.5';
		return $url;
    }
	
	public function getPaypalApi(){
		$data['api_username'] = Mage::getStoreConfig('paypal/wpp/api_username');
		$data['api_password'] = Mage::getStoreConfig('paypal/wpp/api_password');
		$data['api_signature'] = Mage::getStoreConfig('paypal/wpp/api_signature');
		return $data;
	}
	
	//Hai.Ta
	public function getProductsInDeal($productIds){		
		$products = array();
		$collection = Mage::getModel('catalog/product')->getCollection()
					->addFieldToFilter('entity_id', array('in'=>$productIds))
					->addAttributeToSelect('*')
					->addAttributeToSort('price', 'DESC');
		if(count($collection)){
			foreach($collection as $item){				
				$products[] = $item;
			}
		}
		
		return $products;
	}	
	
	public function setDeal($deal){
		Mage::getSingleton('core/session')->setDeal($deal);
	}
	
	public function getDeal(){
		return Mage::getSingleton('core/session')->getDeal();
	}
	
	public function checkProductInDeal($productId){
		$dealId = Mage::getModel('groupdeal/productlist')->getCollection()
					->addFieldToFilter('product_id', $productId)->getLastItem()->getDealId();
					
		$deal = Mage::getModel('groupdeal/deal')->load($dealId);
		
		if($deal->getDealStatus() !=4 && $deal->getDealStatus() !=5){
			return false;
		}		
		
		if(!$this->getDeal()) $this->setDeal($deal);
		return $deal;
	}
	
	public function checkProductForCheckout($chidId, $parentId){
		$ids = Mage::getResourceModel('bundle/selection')->getParentIdsByChild($chidId);		
		
		if(!count($ids) || !in_array($parentId, $ids)) return false;
		
		foreach ($ids as $id){
			if($this->checkProductInDeal($id) != false) return true;			
		}		
		
		return false;
	}
	
	public function checkTypeProduct($type){
		$check = false;
		switch ($type)
		{
			case 'grouped' : $check = true; break;
			case 'configurable' : $check = true; break;
			case 'bundle' : $check = true; break;
			default: break;
		}
		return $check;
		
	}
}