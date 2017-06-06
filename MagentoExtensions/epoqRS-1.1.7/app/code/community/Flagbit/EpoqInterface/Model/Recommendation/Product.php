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
* @version $Id: Product.php 583 2010-11-26 10:08:21Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Model_Recommendation_Product extends Flagbit_EpoqInterface_Model_Recommendation_Abstract {

	protected $_getRecommendationFor = 'Item';
    
    protected function getParamsArray(){
    	
    	$params = array(
    		'productId' 	=> $this->getProductId(),
    	);
    	 	
    	return array_merge($params, parent::getParamsArray());
    }      
    
	
    /**
     * get current Product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProductId()
    {

		return Mage::helper('epoqinterface')->getProductId();
    }  	
    
    
}

