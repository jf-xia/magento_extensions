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
* @version $Id: Data.php 583 2010-11-26 10:08:21Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/
class Flagbit_EpoqInterface_Helper_Data extends Mage_Core_Helper_Abstract
{


    /**
     * get current Product Id
     *
     * @return int
     */
    public function getProductId()
    {
    	$productId = null;
		if(Mage::registry('current_product') instanceof Mage_Catalog_Model_Product
			&& Mage::registry('current_product')->getId()){
			$productId = Mage::registry('current_product')->getId();
			
			if(version_compare(Mage::getVersion(), '1.9.0', '<') 
				&& (string)Mage::getConfig()->getModuleConfig('Enterprise_PageCache')->active == 'true'){
				$processor = Mage::getSingleton('enterprise_pagecache/processor');
	            $cacheId = $processor->getRequestCacheId() . '_current_product_id';
	            Mage::app()->saveCache(Mage::registry('current_product')->getId(), $cacheId);
			}			
			
		}elseif((string)Mage::getConfig()->getModuleConfig('Enterprise_PageCache')->active == 'true'){
			$processor = Mage::getSingleton('enterprise_pagecache/processor');
            $cacheId = $processor->getRequestCacheId() . '_current_product_id';
            if(Mage::app()->loadCache($cacheId)){
            	$productId = Mage::app()->loadCache($cacheId);
            }		
		}

		return $productId;
    }  		
	
}
