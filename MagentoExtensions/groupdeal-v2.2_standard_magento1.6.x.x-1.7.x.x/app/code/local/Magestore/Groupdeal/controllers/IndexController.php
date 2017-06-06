<?php
class Magestore_Groupdeal_IndexController extends Mage_Core_Controller_Front_Action {	
 
	
	public function indexAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){return;}
		//auto set status								
		$categoryId = $this->getRequest()->getParam('category');
		
		$collection = Mage::getModel('groupdeal/deal')->getCollection()
					->addFieldToFilter('deal_status', array('in'=> array(6,5,4))); //deal is active, will active
		
		foreach($collection as $item){
			$item->setStatus();
		}
		
		$deals = Mage::helper('groupdeal')->getSendMailUnreachedDeals();
		foreach($deals as $deal){
			Mage::helper('groupdeal')->sendCancelDealEmailToCustomers($deal);
		}
		
		//if only one deal, redirect to this deal
		$dealProductIds = Mage::helper('groupdeal')->getActiveGroupdealProductIds($categoryId);
		
		if(count($dealProductIds) == 1){
			$deal = Mage::getModel('groupdeal/deal')->loadDealByProduct($dealProductIds[0]);
			$this->_redirectUrl($deal->getDealUrl());
			return;
		}
		
		if (Mage::getSingleton('core/session')->getForCheckout()){
			Mage::getSingleton('core/session')->addError($this->__('Can\'t add more products to cart. Please remove items in cart to continue!'));
		}
		$this->loadLayout();     
		$this->renderLayout();		
    }
	
	public function dealAction(){
		if(!Mage::helper('magenotification')->checkLicenseKeyFrontController($this)){return;}
		$dealId = $this->getRequest()->getParam('id');
		$deal = Mage::getModel('groupdeal/deal')->load($dealId);
		$deal = $deal->setStatus();
		
		if($deal->getDealStatus() == 3 && $deal->getIsSendmailUnreached() == 0){ //deal is unreached and not send mail
			Mage::helper('groupdeal')->sendCancelDealEmailToCustomers($deal);//send email off deal
		}
		
		$this->loadLayout();     
		$this->renderLayout();
	}
	
	public function subscribeAction(){
		
		$this->loadLayout();     
		$this->renderLayout();
	}
	
	public function newsletterAction(){
		try{
			if($this->getRequest()->getPost()){
				$data = $this->getRequest()->getPost();
			
				if($data['category_ids'])
					$data['categories'] = implode(',', $data['category_ids']);
				else
					$data['categories'] = 0;
				
				if(isset($data['unsubscribe']) && $data['unsubscribe'] == 1)
					$data['status'] = 0;
				else
					$data['status'] = 1;
				
				//print_r($data);die();
				$subscriberId = Mage::helper('groupdeal')->getSubscriberId($data['email']);//get Subscribe Id if subscribe exist
				
				$subscriber = Mage::getModel('groupdeal/subscriber')
							->setData($data);
				if($subscriberId)
					$subscriber->setId($subscriberId);
				
				$subscriber->save();
				
				if($subscriber->getStatus() == 1)
					Mage::getSingleton('core/session')->addSuccess($this->__('Subscribed successfull'));
				else
					Mage::getSingleton('core/session')->addSuccess($this->__('Unsubscribed successfull'));
			}
		}catch(Exception $e){
			Mage::getSingleton('core/session')->addError($this->__('Error, please try again'));
		}
		
		$returnUrl = Mage::getSingleton('core/session')->getNewsleterUrl();
		$this->_redirectUrl($returnUrl);
	}
	
	public function whatHappenToYourPurchaseAction(){
		$this->loadLayout();     
		$this->renderLayout();
	}
	
	//check if product has options return pop up else go to cart page Hai.Ta 16.4.2013
	public function getProductOptionAction(){		
		$dealId = $this->getRequest()->getParam('dealId');
		$deal = Mage::getModel('groupdeal/deal')->load($dealId);
		Mage::helper('groupdeal')->setDeal($deal);
		
		$result = array();
		$viewHelper = Mage::helper('catalog/product_view');
		$productHelper = Mage::helper('catalog/product');
		$productId = $this->getRequest()->getParam('productId');
		
		Mage::getSingleton('core/session')->setcheckForPopup(1);
		
		$product = $productHelper->initProduct($productId,$this,null);			
		$product->setFinalPrice(Mage::helper('groupdeal')->getDeal()->getDealPrice());		
		$viewHelper->initProductLayout($product, $this);		
		
		$productBlock = $this->getLayout()->getBlock('groupdeal.product.grouped');		
		
		$result['hasOptions'] = true;
		$result['optionjs'] = $productBlock->getJsItems();	    				
		$result['optionhtml'] = $productBlock->toHtml();
	
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
	
	public function buyAction(){		
        $params = $this->getRequest()->getParams();		
		$productId = $this->getRequest()->getParam('id');
		Mage::getSingleton('core/session')->setIdProductForCheckOut($productId);		
		$productHelper = Mage::helper('catalog/product');
		$product = $productHelper->initProduct($productId,$this,null);			
		$product->setFinalPrice(Mage::helper('groupdeal')->getDeal()->getDealPrice());		
		Mage::getSingleton('core/session')->setSpecialPriceInDeal($product->getSpecialPrice());		
		Mage::getSingleton('core/session')->setcheckForPopup(0);
				
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            } 
			if (isset($params['super_group'])){
				foreach ($params['super_group'] as &$value){
					$value =  '1';
				}
			}
									
			 if (!$product) {
                $this->_redirect('groupdeal');
                return;
            }
			$cart   = $this->_getCart();
			// remove items in cart
			$items = $cart->getItems();	
			Mage::getSingleton('core/session')->setForCheckout(1);
			foreach($items as $item){										
					// remove existed items										
				$cart->removeItem($item->getId());
			}						
			
			try{
				$cart->addProduct($product, $params);										
			}catch(Exception $e){
				Mage::getSingleton('core/session')->addError($this->__('Can\'t add more products to cart.'));
			}		            
			           
            $cart->save();
			
			Mage::getSingleton('core/session')->setForCheckout(0);
			
            $this->_getSession()->setCartWasUpdated(true);
			
			$message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape(Mage::helper('groupdeal')->getDeal()->getDealTitle()));
            $this->_getSession()->addSuccess($message);
					
			$this->_redirect('checkout/cart');
		}catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
            $this->_redirect('groupdeal');
        }
	}
	
	protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }
	
	 protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }
}