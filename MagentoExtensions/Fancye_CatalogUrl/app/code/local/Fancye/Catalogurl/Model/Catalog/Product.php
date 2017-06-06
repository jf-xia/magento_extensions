<?php
class Fancye_Catalogurl_Model_Catalog_Product extends Mage_Catalog_Model_Product
{
    public function getProductUrl($useSid = null)
    {
        return Mage::getBaseUrl().'p'.$this->getId().'-'.$this->getUrlKey().'.html';//jack$this->getUrlModel()->getProductUrl($this, $useSid);
    }
}
		