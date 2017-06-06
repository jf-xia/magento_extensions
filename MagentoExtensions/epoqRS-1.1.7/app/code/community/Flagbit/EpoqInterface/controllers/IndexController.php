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
* @version $Id: IndexController.php 673 2011-07-27 14:18:59Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_IndexController extends Mage_Core_Controller_Front_Action {
	
    const XML_STATUS_PATH			= 'epoqinterface/export/enabled';		
    const XML_AUTH_USERNAME_PATH	= 'epoqinterface/export/username';		
    const XML_AUTH_PASSWORD_PATH	= 'epoqinterface/export/password';		
	
    public function preDispatch()
    {

    	// check if export is enabled
    	if(!Mage::getStoreConfig(self::XML_STATUS_PATH)){	
    		$this->setFlag('', self::FLAG_NO_DISPATCH, true);	
    	}
    	
    	//$username = Mage::getStoreConfig(self::XML_AUTH_USERNAME_PATH);
    	$password = Mage::getStoreConfig(self::XML_AUTH_PASSWORD_PATH);
    	
    	if(!empty($password) 
    		&& $password != $this->getRequest()->getParam('pass')){
    			
    		$this->setFlag('', self::FLAG_NO_DISPATCH, true);
    	}       	
    	   	
    	// Authentication 
    	/*
    	if(!empty($username)
    		&& !empty($password)
    		&& ($this->getRequest()->getServer('PHP_AUTH_USER') != $username
    		or $this->getRequest()->getServer('PHP_AUTH_PW') != $password)){
    	
	    	$this->getResponse()->setHeader('status', 'Unauthorized', true);
	    	$this->getResponse()->setHeader('WWW-authenticate', 'basic realm="epoq Interface"', true);
	    	$this->getResponse()->sendHeaders();
	    	$this->setFlag('', self::FLAG_NO_DISPATCH, true);
    	}*/
    	
        return parent::preDispatch();
    }	
	
	public function indexAction(){
		
		$this->_forward('productlist');
	}
	
	
	public function productlistAction(){
		$this->getResponse()->setHeader('Content-type', 'text/plain; charset=UTF-8');
		//$this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
		
		$this->getResponse()->setBody($this->getLayout()->createBlock('epoqinterface/export_productlist')->toHtml());		
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
			$key = preg_replace('/[^a-z0-9\_]/i', '', $key);
			
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
					strstr($value, array('<','>'))
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

