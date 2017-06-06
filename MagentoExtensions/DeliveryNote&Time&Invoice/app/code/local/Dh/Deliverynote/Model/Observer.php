<?php

/**
 * Delivery Note Observer Model
 *
 * @category	Dh
 * @package		Dh_Deliverynote
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Dh_Deliverynote_Model_Observer extends Mage_Core_Helper_Abstract
{        
	/**
	 * Take the note from post and and store it in the current quote.
	 * 
	 * When the quote gets converted we will store the delivery note
	 * and assign to the order
	 *
	 * @param Varien_Object $observer
	 * @return Dh_Deliverynote_Model_Observer
	*/
    public function checkoutEventCreateDeliveryNote($observer)
    {
		$note = $observer->getEvent()->getRequest()->getParam('deliverynote-note');
		$time = $observer->getEvent()->getRequest()->getParam('deliverynote-time');
		$invoices_type = $observer->getEvent()->getRequest()->getParam('invoices_type');
		$invoices_title = $observer->getEvent()->getRequest()->getParam('invoices_title');
		$invoices_company = $observer->getEvent()->getRequest()->getParam('invoices_company');
		$taxpayer_id = $observer->getEvent()->getRequest()->getParam('taxpayer_id');
		$invoices_address = $observer->getEvent()->getRequest()->getParam('invoices_address');
		$invoices_phone = $observer->getEvent()->getRequest()->getParam('invoices_phone');
		$invoices_bank = $observer->getEvent()->getRequest()->getParam('invoices_bank');
		$invoices_account = $observer->getEvent()->getRequest()->getParam('invoices_account');
		$invoices_content = $observer->getEvent()->getRequest()->getParam('invoices_content');

		if ($invoices_type=="增值税发票") {
			$invoice = $invoices_type."^".$invoices_title.":".$invoices_company."^纳税人识别号:".$taxpayer_id."^注册地址:".$invoices_address."^注册电话:".$invoices_phone."^开户银行:".$invoices_bank."^银行帐户:".$invoices_account."^发票内容:".$invoices_content;
		} else {
	    	$invoice = $invoices_type."^".$invoices_title.":".$invoices_company."^发票内容:".$invoices_content;
		}
	      
		$quote=$observer->getEvent()->getQuote();
			
		if (! empty($note)) {		
			$quote->setDeliveryNote((string)$note);
		}
		if (! empty($time)) {		
			$quote->setDeliveryTime((string)$time);
		}
		if (! empty($invoice)) {		
			$quote->setDeliveryInvoice((string)$invoice);
		}
		$quote->save();
        return $this;
    }
    
	/**
	 * If the quote has a delivery note then lets save that note and 
	 * assign the id to the order
	 * 
	 * @param Varien_Object $observer
	 * @return Dh_Deliverynote_Model_Observer
	*/
    public function salesEventConvertQuoteToOrder($observer)
    {
		if ($note = $observer->getEvent()->getQuote()->getDeliveryNote()) {	
			$deliveryNote = Mage::getModel('deliverynote/note')->setNote($note);
			Mage::log("~~~~~~~~~~~~~~~~~~~~~~~~~~note=".$note);
			if ($invoice = $observer->getEvent()->getQuote()->getDeliveryInvoice()) {		
				$deliveryInvoice = $deliveryNote->setInvoice($invoice);
				Mage::log("~~~~~~~~~~~~~~~~~~~~~~~~~~invoice=".$invoice);
				if ($time = $observer->getEvent()->getQuote()->getDeliveryTime()) {		
					$deliveryTime = $deliveryInvoice->setTime($time)->save();
					Mage::log("~~~~~~~~~~~~~~~~~~~~~~~~~~time=".$time);
					
					$observer->getEvent()->getOrder()->setDeliveryNoteId($deliveryTime->getDeliveryNoteId());
				}
			}
		}
        return $this;
    }
}