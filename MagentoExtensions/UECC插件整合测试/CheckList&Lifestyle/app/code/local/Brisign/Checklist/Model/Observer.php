<?php

/**
 * Checklist Checknote Observer Model
 *
 * @category	Brisign
 * @package		Brisign_Checklist
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Checklist_Model_Observer extends Mage_Core_Helper_Abstract
{        
	/**
	 * Take the checknote from post and and store it in the current quote.
	 * 
	 * When the quote gets converted we will store the checklist checknote
	 * and assign to the order
	 *
	 * @param Varien_Object $observer
	 * @return Brisign_Checklist_Model_Observer
	*/
    public function checkoutEventCreateChecklistChecknote($observer)
    {
        $checknote = $observer->getEvent()->getRequest()->getParam('checklist-checknote');
        
		if (! empty($checknote)) {		
			$observer->getEvent()->getQuote()->setChecklistChecknote((string)$checknote)->save();
		}
        return $this;
    }
    
	/**
	 * If the quote has a checklist checknote then lets save that checknote and 
	 * assign the id to the order
	 * 
	 * @param Varien_Object $observer
	 * @return Brisign_Checklist_Model_Observer
	*/
    public function salesEventConvertQuoteToOrder($observer)
    {
		if ($checknote = $observer->getEvent()->getQuote()->getChecklistChecknote()) {		
			$checklistChecknote = Mage::getModel('checklist/checknote')->setChecknote($checknote)->save();
			
			$observer->getEvent()->getOrder()
				->setChecklistChecknoteId($checklistChecknote->getChecklistChecknoteId());
		}
        return $this;
    }
}