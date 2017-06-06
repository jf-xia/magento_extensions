<?php

class Yoast_Catalog_Model_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
	 /**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl()
    {
		$cururl = Mage::helper('core/url')->getCurrentUrl();
		$pos = strpos($cururl,"catalogsearch");

		if (($this->getFilter() instanceof Mage_Catalog_Model_Layer_Filter_Category)&&($pos === false)) 
		{           
			  $category = Mage::getModel('catalog/category')->load($this->getValue());
			  $url = $category->getUrl();
			  $query = array ( Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null );
			  $urler = Mage::getModel('core/url');
			  $urler -> getRouteUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
			  $urler->setQueryParams($query, true);
			  $query = $urler->getQuery(false);
			  if (!$query)
				{
					return $url;
				}
				else
				{
					return $url . '?' . $query;
				};
		}
		else 
		{
			  $query = array(
				$this->getFilter()->getRequestVar()=>$this->getValue(),
				Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
			  );
			
			  return Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
        }

    }
}