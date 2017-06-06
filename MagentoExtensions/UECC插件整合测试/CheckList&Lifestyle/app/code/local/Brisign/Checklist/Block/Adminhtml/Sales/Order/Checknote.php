<?php

/**
 * Checklist Block
 *
 * @category	Brisign
 * @package		Brisign_Checklist
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Checklist_Block_Adminhtml_Sales_Order_Checknote extends Mage_Adminhtml_Block_Template
{
    private $_checknote;

	/**
	 * Based on the object being viewed i.e. order, invoice etc then 
	 * lets get the checknote from the order if available
	 * 
	 * @return void
	*/
    private function _initChecknote()
    {
		$checknoteId = '';
		
        if (! is_null(Mage::registry('current_order'))) {
            $checknoteId = Mage::registry('current_order')->getData('checklist_checknote_id');
        }
        elseif(! is_null(Mage::registry('current_shipment'))) {
            $checknoteId = Mage::registry('current_shipment')->getOrder()->getData('checklist_checknote_id');  
        }
        elseif(! is_null(Mage::registry('current_invoice'))) {
            $checknoteId = Mage::registry('current_invoice')->getOrder()->getData('checklist_checknote_id'); 
        }
		elseif(! is_null(Mage::registry('current_creditmemo'))) {
			$checknoteId = Mage::registry('current_creditmemo')->getOrder()->getData('checklist_checknote_id'); 
		}
		
		if ($checknoteId != '') {
			$this->_checknote = Mage::getModel('checklist/checknote')->load($checknoteId)->getChecknote();
		}
    }

	/**
	 * Initialise the checklist instruction and return
	 *
	 * @return mixed bool|string
	*/
    protected function getChecknote()
    {
       if (is_null($this->_checknote)) {
            $this->_initChecknote();
       }
	   return empty($this->_checknote) ? false : $this->_checknote;
    }
}