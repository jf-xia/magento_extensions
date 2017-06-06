<?php
/*                                                                       *
* This script is part of the epoq Recommendation Service project         *
*                                                                        *
* epoqinterface is free software; you can redistribute it and/or modify  *
* it under the terms of the GNU General Public License version 2 as      *
* published by the Free Software Foundation.                             *
*                                                                        *
* This script is distributed in the hope that it will be useful, but     *
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
* TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
* Public License for more details.                                       *
*                                                                        *
* @version $Id: Cart.php 487 2010-08-05 12:32:57Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Block_Track_Cart extends Flagbit_EpoqInterface_Block_Abstract
{

	protected $_quote = null;
	
    protected function _toHtml()
    {
    	if (!$this->_beforeToHtml()
    		or (!$this->getSession()->getCartUpdate() && $this->getRequest()->getControllerName() == 'cart')
    		&& !$this->getSession()->getBlockTrackCartOutput()) {
    		#return '';
    	}

    	$output = $this->getSession()->getBlockTrackCartOutput();
    	
    	if($output){
    		$this->getSession()->unsBlockTrackCartOutput();
    		$this->getSession()->unsCartUpdate();
    		#return $output;
    	}

    	$variables = array(
    		'epoq_tenantId'		=> Mage::getStoreConfig('epoqinterface/config/tenant_id'),
    		'epoq_sessionId'	=> Mage::getSingleton('core/session')->getSessionId(),
    	);    	
    	

		$function = $this->getSession()->getCartUpdate() == 'process' ? 'processCart' : 'updateCart';
		
		$this->getSession()->unsCartUpdate();

	    $data = array(
	        'action' => $function
	    );	    
	    	
		return $this->getJavascriptOutput(
					$this->arrayToString(
						$this->getParamsArray()
					), 
					$function);
    }	
    
    protected function getParamsArray(){
    	
    	$variables = parent::getParamsArray();
    	
    	$items = $this->getQuote()->getAllVisibleItems();
		
		/*@var $item Mage_Sales_Model_Quote_Item */
		foreach ($items as $key => $item){
	
			if ($option = $item->getOptionByCode('simple_product')) {
				
            	$product = $option->getProduct();
            	$variables['epoq_variantOfList'][$key] = $item->getProduct()->getId();
        	}else{
        		$product = $item->getProduct();		
        	}			
			
			$variables['epoq_productIds'][$key] = $product->getId();
			$variables['epoq_quantities'][$key] = $item->getQty();
			$variables['epoq_unitPrices'][$key] = $item->getPrice();
		}
    	
    	return $variables;
    }
	
    /**
     * get Quote
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function getQuote(){
    	
    	if($this->_quote === null){
    		$this->_quote = Mage::getSingleton('checkout/cart')->getQuote();
    	}
    	
    	return $this->_quote;
    }
    
    public function setQuote($quote){
    	
    	$this->_quote = $quote;
    	
    	return $this;
    }
    
}