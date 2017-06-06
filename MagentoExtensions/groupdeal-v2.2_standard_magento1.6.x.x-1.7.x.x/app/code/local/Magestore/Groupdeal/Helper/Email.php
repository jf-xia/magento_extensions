<?php
class Magestore_Groupdeal_Helper_Email extends Mage_Core_Helper_Abstract{
	const XML_PATH_EMAIL_IDENTITY = 'trans_email/ident_sales';
	const XML_PATH_BUY_WAITING_DEAL_EMAIL = 'groupdeal/email/buy_waiting_deal_template';
    const XML_PATH_BUY_ON_DEAL_EMAIL = 'groupdeal/email/buy_on_deal_template';
	const XML_PATH_ON_DEAL_EMAIL = 'groupdeal/email/on_deal_template';
	const XML_PATH_CANCEL_DEAL_EMAIL = 'groupdeal/email/cancel_deal_template';
	const XML_PATH_OPENED_DEAL_EMAIL = 'groupdeal/email/opened_deal_template';
		
	public function sendWaitingDealEmailWhenBuy($deal, $groupdealOrder){
		$customerId = Mage::getModel('sales/order')->load($groupdealOrder->getOrderId())->getCustomerId();
		$customer = Mage::getModel('customer/customer')->load($customerId);
		
		$translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		$template = Mage::getStoreConfig(self::XML_PATH_BUY_WAITING_DEAL_EMAIL);
		
		$recipient = array(
                		'email' => $customer->getEmail(),
                		'name'  => $customer->getName(),
            		);
		
		$mailTemplate = Mage::getModel('core/email_template');
		
		$storeId = Mage::app()->getStore()->getId();
		$mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$storeId))
			->sendTransactional(
				$template,
				Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId),
				$recipient['email'],
				$recipient['name'],
				array(
					'customer'  => $customer,
					'deal'		=> $deal,
					'order'		=> $groupdealOrder->setMyDealUrl(Mage::getModel('core/url')->getUrl('groupdeal/mydeal/index', array('id'=>$groupdealOrder->getId()))),
				)
			);
		
		$translate->setTranslateInline(true);
		return $groupdealOrder;	
	}
	
	public function sendOnDealEmailWhenBuy($deal, $groupdealOrder){
		$customerId = Mage::getModel('sales/order')->load($groupdealOrder->getOrderId())->getCustomerId();
		$customer = Mage::getModel('customer/customer')->load($customerId);
		
		$translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		$template = Mage::getStoreConfig(self::XML_PATH_BUY_ON_DEAL_EMAIL);
		
		$recipient = array(
                		'email' => $customer->getEmail(),
                		'name'  => $customer->getName(),
            		);
		
		$mailTemplate = Mage::getModel('core/email_template');
		
		$storeId = Mage::app()->getStore()->getId();
		$mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$storeId))
			->sendTransactional(
				$template,
				Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId),
				$recipient['email'],
				$recipient['name'],
				array(
					'customer'  => $customer,
					'deal'		=> $deal,
					'order'		=> $groupdealOrder->setMyDealUrl(Mage::getModel('core/url')->getUrl('groupdeal/mydeal/index', array('id'=>$groupdealOrder->getId()))),
				)
			);
		
		$translate->setTranslateInline(true);
		return $groupdealOrder;	
	}
	
	public function sendOnDealEmailToCustomer($deal, $groupdealOrder){
		$customerId = Mage::getModel('sales/order')->load($groupdealOrder->getOrderId())->getCustomerId();
		$customer = Mage::getModel('customer/customer')->load($customerId);
		
		$translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		$template = Mage::getStoreConfig(self::XML_PATH_ON_DEAL_EMAIL);
		
		//die($template);
		
		$recipient = array(
                		'email' => $customer->getEmail(),
                		'name'  => $customer->getName(),
            		);
		
		$mailTemplate = Mage::getModel('core/email_template');
		
		$storeId = Mage::app()->getStore()->getId();
		$mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$storeId))
			->sendTransactional(
				$template,
				Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId),
				$recipient['email'],
				$recipient['name'],
				array(
					'customer'  => $customer,
					'deal'		=> $deal,
					'order'		=> $groupdealOrder->setMyDealUrl(Mage::getModel('core/url')->getUrl('groupdeal/mydeal/index', array('id'=>$groupdealOrder->getId()))),
				)
			);
		
		$translate->setTranslateInline(true);
		return $groupdealOrder;	
	}
	
	public function sendCancelDealEmailToCustomer($deal, $groupdealOrder){
		$customerId = Mage::getModel('sales/order')->load($groupdealOrder->getOrderId())->getCustomerId();
		$customer = Mage::getModel('customer/customer')->load($customerId);
		
		$translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		$template = Mage::getStoreConfig(self::XML_PATH_CANCEL_DEAL_EMAIL);
		
		$recipient = array(
                		'email' => $customer->getEmail(),
                		'name'  => $customer->getName(),
            		);
		
		$mailTemplate = Mage::getModel('core/email_template');
		
		$storeId = Mage::app()->getStore()->getId();
		$mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$storeId))
			->sendTransactional(
				$template,
				Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId),
				$recipient['email'],
				$recipient['name'],
				array(
					'customer'  => $customer,
					'deal'		=> $deal,
					'order'		=> $groupdealOrder->setMyDealUrl(Mage::getModel('core/url')->getUrl('groupdeal/mydeal/index', array('id'=>$groupdealOrder->getId()))),
				)
			);
		
		$translate->setTranslateInline(true);
		return $groupdealOrder;	
	}
	
	public function sendOpenDealEmailToSubscribers($deal){
		$translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		$template = Mage::getStoreConfig(self::XML_PATH_OPENED_DEAL_EMAIL);
		$mailTemplate = Mage::getModel('core/email_template');
		$storeId = Mage::app()->getStore()->getId();
		
		foreach($deal->getSubscribers() as $subscriber){
			$subscribeUrl = $this->_getUrl('groupdeal/index/subscribe', array('e' => base64_encode($subscriber['email'])));
			$mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$storeId))
				->sendTransactional(
					$template,
					Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId),
					$subscriber['email'],
					$subscriber['name'],
					array(
						'subscriber'  => $subscriber,
						'deal'		=> $deal->setSubscribeUrl($subscribeUrl),
					)
				);
			
			$translate->setTranslateInline(true);
		}
	}
}