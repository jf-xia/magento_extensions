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
* @version $Id: Productlist.php 884 2011-09-01 13:34:31Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Block_Export_Productlist extends Flagbit_EpoqInterface_Block_Abstract
{
    
    
    /**
     * Product Type Instances singletons
     *
     * @var array
     */
    protected $_productTypeInstances = array();    

    /**
     * generates the Output
     *
     * @return string
     */
    protected function _toHtml()
    { 
		// create XML Object
       	$xmlObj = new DOMDocument("1.0", "UTF-8");
		$xmlObj->formatOutput = true;
		
		// add RSS Element and Namespace
		$elemRss = $xmlObj->createElement( 'rss' );
		$elemRss->setAttribute ( 'version' , '2.0' );
		$elemRss->setAttribute ( 'xmlns:g' , 'http://base.google.com/ns/1.0' );
		$elemRss->setAttribute ( 'xmlns:e' , 'http://base.google.com/cns/1.0' );
		$elemRss->setAttribute ( 'xmlns:c' , 'http://base.google.com/cns/1.0' );
		$xmlObj->appendChild( $elemRss );
		
		// add Channel Element
		$elemChannel = $xmlObj->createElement( 'channel' );
		$elemRss->appendChild( $elemChannel );
  			
		// get Products
        $product = Mage::getModel('catalog/product');
        
        /*@var $products Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection */
        $products = $product->getCollection()
            ->addStoreFilter()
            ->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left') 
            ->addAttributeToSelect(array('name', 'short_description', 'price', 'image'), 'inner');
            //->addAttributeToSelect(array('special_price', 'special_from_date', 'special_to_date'), 'left');
            
        // split Export in Parts 
        if($this->getRequest()->getParam('part')){    
        	$products->getSelect()->limitPage($this->getRequest()->getParam('part', 1), $this->getRequest()->getParam('limit', 1000));
        }
        
        Mage::helper('epoqinterface/debug')->log('Export select query: '.(string) $products->getSelect());
         
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        //Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);

        /*
        using resource iterator to load the data one by one
        instead of loading all at the same time. loading all data at the same time can cause the big memory allocation.
        */
        Mage::getSingleton('core/resource_iterator')
            ->walk($products->getSelect(), array(array($this, 'addNewItemXmlCallback')), array('xmlObj'=> $xmlObj, 'product'=>$product));

        return $xmlObj->saveXML();	
    }    

    /**
     * Product iterator callback function
     * add detailinformations to products
     *
     * @param array $args
     */
    public function addNewItemXmlCallback($args)
    {
        $product = $args['product'];
        $this->setData('product', $product);
        
        // reset time limit
        @set_time_limit(30);		
     
        /*@var $product Mage_Catalog_Model_Product */
        $product->setData($args['row']);
        $product->load($product->getId());     
        
        $this->setProductTypeInstance($product);        
        
        $parentProduct = array();
        if(method_exists($product, 'getParentProductIds')){
        	$product->loadParentProductIds();
        	$parentProduct = $product->getParentProductIds();
        }

        // get Productcategory
        $category = $product->getCategoryCollection()->load()->getFirstItem();
        $this->setData('category', $category);
    
        /*@var $xmlObj DOMDocument*/
        $xmlObj = $args['xmlObj'];

        // create Item xml Element
        $elemItem = $xmlObj->createElement('item');

        $data = array(
            'title'         => $product->getName(),
            'link'          => $product->getProductUrl(),        
        
        	// g Namespace
        	'g:id'			=> $product->getId(),
            'description'   => $product->getShortDescription(),
    		'g:price'		=> $this->getProductPrice($product),
        	'g:image_link'		=> (string) $this->helper('catalog/image')->init($product, 'image'),
        	'g:product_type'=> implode('>', $this->getCategoryPath(true)),
        	'g:brand'		=> is_object($this->getProduct()->getResource()
	            ->getAttribute('manufacturer')) ? $this->getProduct()->getAttributeText('manufacturer') : '',
        	'g:upc'			=> $this->getProduct()->getSku(),
        	'g:quantity'	=> $this->getProduct()->isSaleable(),	
	        'g:visibility'	=> $this->getProduct()->getVisibility(),	    
         
        	// e Namespace
        	'e:locakey'		=> substr(Mage::getSingleton('core/locale')->getLocale(), 0, 2),
        
        	// c Namespace
        	'c:mgtproducttype'	=> $product->getTypeId(),     
        	        	
		);
		
		
		
		// set Product variant
		if(isset($parentProduct[0])){
			$data['e:variant_of'] = $parentProduct[0];
			$data['c:mgt_parents'] = implode(',', $parentProduct);
		}
    	if($product->isConfigurable() 
    		&& $product->getTypeInstance(true) instanceof Mage_Catalog_Model_Product_Type_Configurable){
			$data['e:variants'] = implode(',', $this->_getVariantIds($product));
		}		
		
		// add Product Attributes
		$attributes = $this->getProductAttributes();
		foreach($attributes as $key => $value){
			$data['c:'.$key] = $value;
		}
		
		// translate array to XML
        $this->dataToXml($data, 'data', $elemItem, $xmlObj); 

        // add Product to Channel Element
        /*@var $elemChannel DOMNodeList */
        $elemChannel = $xmlObj->getElementsByTagName('channel');
        $elemChannel->item(0)->appendChild( $elemItem );

    }
    
    /**
     * ReDefine Product Type Instance to Product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Mage_Catalog_Model_Convert_Adapter_Product
     */
    public function setProductTypeInstance(Mage_Catalog_Model_Product $product)
    {
        $type = $product->getTypeId();
        if (!isset($this->_productTypeInstances[$type])) {
            $this->_productTypeInstances[$type] = Mage::getSingleton('catalog/product_type')
                ->factory($product, true);
        }
        $product->setTypeInstance($this->_productTypeInstances[$type], true);
        return $this;
    }    
    
    /**
     * get Product Variants
     * 
     * @param Mage_Catalog_Model_Product $product
     * @return array
     */
    protected function _getVariantIds($product){
    
    	$childProducts = $product->getTypeInstance(true)->getUsedProducts(null, $product);
    	$childProductIds = array();
		foreach((array) $childProducts as $childProduct){
			if ($childProduct->isSaleable()) {
				$childProductIds[] = $childProduct->getId();
			}
		}
		return $childProductIds;    
    }     
    
    /**
     * get current Product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
    	return $this->getData('product');
    }   
    

    /**
     * get current Category
     *
     * @return unknown
     */
    public function getCategory()
    {
    	return $this->getData('category');
    }     
      

	/**
	 * The main function for converting to an XML document.
	 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
	 *
	 * @param array $data
	 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
	 * @param DomElement $elem - should only be used recursively
	 * @param DOMDocument $xml - should only be used recursively
	 * @return object DOMDocument
	 */
	protected function dataToXml($data, $rootNodeName = 'data', $elem=null, $xml=null)
	{
		
		if ($xml === null)
		{
			$xml = new DOMDocument("1.0", "UTF-8");
			$xml->formatOutput = true;
			$elem = $xml->createElement( $rootNodeName );
  			$xml->appendChild( $elem );
		}
		
		// loop through the data passed in.
		foreach($data as $key => $value)
		{
			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				// make string key...
				$key = "node_". (string) $key;
			}
			
			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z0-9\_\:]/i', '', $key);
			
			// if there is another array found recrusively call this function
			if (is_array($value))
			{
				$subelem = $xml->createElement( $key );
				$elem->appendChild( $subelem);
				
				// recrusive call.
				$this->DataToXml($value, $rootNodeName, $subelem, $xml);
			}
			else 
			{
				$subelem = $xml->createElement( $key );
				$subelem->appendChild(
					(
						strpos($value, '<')
						or strpos($value, '>')
						or strpos($value, '&')
					)
					? $xml->createCDATASection( $value )
					: $xml->createTextNode( $value )
				);
				$elem->appendChild( $subelem );

			}
		}
		
		// pass back as DOMDocument object
		return $xml;
	}	
    
}