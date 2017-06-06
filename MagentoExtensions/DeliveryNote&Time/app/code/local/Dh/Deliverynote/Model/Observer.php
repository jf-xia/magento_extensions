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
        
		if (! empty($note)) {		
			$observer->getEvent()->getQuote()->setDeliveryNote((string)$note)->save();
		}
		if (! empty($time)) {		
			$observer->getEvent()->getQuote()->setDeliveryTime((string)$time)->save();
		}
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
			//Mage::log("~~~~~~~~~~~~~~~~~~~~~~~~~~note=".$note);
			if ($time = $observer->getEvent()->getQuote()->getDeliveryTime()) {		
				$deliveryN = $deliveryNote->setTime($time)->save();
				//Mage::log("~~~~~~~~~~~~~~~~~~~~~~~~~~time=".$time);
				
				$observer->getEvent()->getOrder()->setDeliveryNoteId($deliveryN->getDeliveryNoteId());
			}
		}
        return $this;
    }
}