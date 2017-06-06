<?php

/**
 * Deliverydate Block
 *
 * @category	Dh
 * @package		Dh_Deliverydate
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Dh_Deliverydate_Block_Adminhtml_Sales_Order_Date extends Mage_Adminhtml_Block_Template
{
    private $_date;

	/**
	 * Based on the object being viewed i.e. order, invoice etc then 
	 * lets get the date from the order if available
	 * 
	 * @return void
	*/
    private function _initDate()
    {
		$dateId = '';
		
        if (! is_null(Mage::registry('current_order'))) {
            $dateId = Mage::registry('current_order')->getData('delivery_date_id');
        }
        elseif(! is_null(Mage::registry('current_shipment'))) {
            $dateId = Mage::registry('current_shipment')->getOrder()->getData('delivery_date_id');  
        }
        elseif(! is_null(Mage::registry('current_invoice'))) {
            $dateId = Mage::registry('current_invoice')->getOrder()->getData('delivery_date_id'); 
        }
		elseif(! is_null(Mage::registry('current_creditmemo'))) {
			$dateId = Mage::registry('current_creditmemo')->getOrder()->getData('delivery_date_id'); 
		}
		
		if ($dateId != '') {
			$this->_date = Mage::getModel('deliverydate/date')->load($dateId)->getDate();
		}
    }

	/**
	 * Initialise the delivery instruction and return
	 *
	 * @return mixed bool|string
	*/
    protected function getDate()
    {
       if (is_null($this->_date)) {
            $this->_initDate();
       }
	   return empty($this->_date) ? false : $this->_date;
    }
}