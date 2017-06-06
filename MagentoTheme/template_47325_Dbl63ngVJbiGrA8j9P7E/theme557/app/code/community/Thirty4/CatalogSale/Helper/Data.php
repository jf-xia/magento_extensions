<?php

class Thirty4_CatalogSale_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getCatalogSaleUrl()
	{
		return $this->_getUrl('catalogsale');
	}
}