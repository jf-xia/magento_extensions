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

class Flagbit_EpoqInterface_Block_Track_Product extends Flagbit_EpoqInterface_Block_Abstract
{
	
    protected $_categoryPath;	
	
    /**
     * Internal constructor, that is called from real constructor
     *
     */
    protected function _construct(){
    		
    	if($this->getSession()->getLastRecommentationId()
    		&& $this->_lastRecommentationId === null){
    		if(in_array($this->getProduct()->getId(), (array) $this->getSession()->getLastRecommentationProducts())){
    		
    			$this->_lastRecommentationId = $this->getSession()->getLastRecommentationId();
    		}
    	}	
    	
    	parent::_construct();
    }      
    
    protected function _toHtml()
    {

    	if (!$this->_beforeToHtml()
    		or !$this->getProduct() instanceof Mage_Catalog_Model_Product) {
    		return '';
    	}
        
		return $this->getJavascriptOutput(
					$this->arrayToString(
						$this->getParamsArray()
					), 
					'viewItem');        
    }
    
	protected function getParamsArray(){
		
    	$variables = array(
    		'epoq_productId'	=> $this->getProduct()->getId(),
    		'epoq_name'			=> $this->getProduct()->getName(),
    		'epoq_price'		=> $this->getProductPrice($this->getProduct()),
    		'epoq_productUrl'	=> $this->getProduct()->getProductUrl(),
    		'epoq_smallImage'	=> (string) $this->helper('catalog/image')->init($this->getProduct(), 'small_image')->resize(135, 135),
    		'epoq_category'		=> implode('>', $this->getCategoryPath()),
    		'epoq_brand'		=> $this->getProduct()->getManufacturer(),
    		'epoq_largeImage'	=> (string) $this->helper('catalog/image')->init($this->getProduct(), 'image'),
    		'epoq_description'	=> $this->getProduct()->getDescription(),
    		'epoq_inStock'		=> ($this->getProduct()->isSaleable() ? 'true' : 'false'),
    		'epoq_attributes'	=> $this->getProductAttributes(),
    		'epoq_locakey'		=> substr(Mage::getSingleton('core/locale')->getLocale(), 0, 2)
    	);

		return array_merge(parent::getParamsArray(), $variables);
	}
    
        
     
}