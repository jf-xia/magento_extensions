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
* @version $Id: Cart.php 5 2009-07-03 09:22:08Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Model_Customer_Profiles extends Flagbit_EpoqInterface_Model_Abstract {


	/**
	 * send Customer Data
	 *
	 * @param Mage_Sales_Model_Order $order
	 */
	public function send($order){
		
		if(!Mage::getStoreConfig(self::XML_CUSTOMER_PROFILES_PATH)){
			return;
		}
		
	
		/*@var $address Mage_Sales_Model_Order_Address */
		$address = $order->getBillingAddress();
		
		$customerData = array(
    		'tenantId'		=> Mage::getStoreConfig(self::XML_TENANT_ID_PATH),
    		'sessionId'		=> Mage::getSingleton('core/session')->getSessionId(), 		
			'customerId'	=> $order->getCustomerId(),
			'firstName'		=> $address->getFirstname(),
			'lastName'		=> $address->getLastname(),
			'sex'			=> '',
			'title'			=> '',
			'street'		=> preg_replace('([0-9]*)', '', (string) $address->getData('street')),
			'house'			=> preg_replace('/([^0-9]*)/', '', (string) $address->getData('street')),
			'city'			=> $address->getCity(),
			'zip'			=> $address->getPostcode(),
			'phone'			=> $address->getTelephone(),
			'country'		=> $address->getCountryId()
		);

		/*@var $client Zend_Rest_Client*/
		$client = $this->getRestClient();
		
		/*@var $httpClient Zend_Http_Client */
		$httpClient = $client->getHttpClient();
		$result = $httpClient
					->setUri($this->getRestUrl().'setAddress?tenantId='.Mage::getStoreConfig(self::XML_TENANT_ID_PATH).'&')
					->setParameterPost($customerData)
					->request(Zend_Http_Client::POST);
		
	}
	
    
}

