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
class Flagbit_EpoqInterface_Block_Track_Order extends Flagbit_EpoqInterface_Block_Abstract
{
	
    protected function _toHtml()
    {
        $output = '';
        $orderIds = $this->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        
        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('entity_id', array('in' => $orderIds))
        ;        
        

    	$variables = array(
    		'epoq_tenantId'		=> Mage::getStoreConfig('epoqinterface/config/tenant_id'),
    		'epoq_sessionId'	=> Mage::getSingleton('core/session')->getSessionId(),
    	);    	
    	
    	foreach($collection as $order){
    	    $this->setOrder($order);
    		$output .= $this->getJavascriptOutput(
    					$this->arrayToString(
    						$this->getParamsArray()
    					), 
    					'processCart');
    	}
    	
        return $output;					
    }	
    
    protected function getParamsArray(){
    	
    	$variables = parent::getParamsArray();
    	
    	$items = $this->getOrder()->getAllVisibleItems();
		
		/*@var $item Mage_Sales_Model_Quote_Item */
		foreach ($items as $key => $item){
	
			if ($option = $item->getOptionByCode('simple_product')) {			
            	$variables['epoq_variantOfList'][$key] = $item->getProductId();
        	}		
			
			$variables['epoq_productIds'][$key] = $this->jsQuoteEscape($item->getProductId());
			$variables['epoq_quantities'][$key] = $this->jsQuoteEscape($item->getQtyOrdered());
			$variables['epoq_unitPrices'][$key] = $this->jsQuoteEscape($item->getBasePrice());
		}
    	
    	return $variables;
    }

}