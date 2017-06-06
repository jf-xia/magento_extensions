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
* @version $Id: Abstract.php 6 2009-07-03 13:40:19Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Model_Abstract extends Mage_Core_Model_Abstract {
	
    const XML_REST_URL_PATH				= 'system/epoqinterface/rest_url';	
    const XML_TENANT_ID_PATH			= 'epoqinterface/config/tenant_id';	
    const XML_TIMEOUT_PATH				= 'epoqinterface/config/timeout';	
    const XML_CUSTOMER_PROFILES_PATH	= 'epoqinterface/config/customer_profiles';	
    const XML_ERROR_HANDLING_PATH		= 'epoqinterface/error_handling/enabled';	
    const XML_MAX_ATTEMPTS_PATH			= 'epoqinterface/error_handling/max_attempts';	
    const XML_IDLE_TIME_PATH			= 'epoqinterface/error_handling/idle_time';	
    const XML_DEMO_PATH					= 'epoqinterface/config/demo';	
	const CACHE_REQUEST_FAILURE_COUNTER	= 'epoqinterface_rest_failure_counter';
	const CACHE_REQUEST_FAILURE_TIME	= 'epoqinterface_rest_failure_time';
    
	protected $_restClient= null;
	

    
    /**
     *	do REST Request an handle Error
     * 
     * @return Zend_Rest_Client_Result | null
     */
    protected function _doRequest(){
    	
    	// error handling
    	if($this->getIsErrorHandling() 
    		&& $this->getFailureCount() >= Mage::getStoreConfig(self::XML_MAX_ATTEMPTS_PATH)
    		&& $this->getRequestFailureTime() > time()){
			Mage::helper('epoqinterface/debug')->log('error handling is on: '.$this->getRequestFailureTime().' > '.time());
    		return null;
    	}
    	
    	try{
    		/*@var $result Zend_Rest_Client_Result */
			$result = $this->getRestClient()->get();
			
    		Mage::helper('epoqinterface/debug')
    			->log('Request URI: '.$this->getRestClient()->getUri()->getUri())
    			->log('Response: '.$this->getRestClient()->getHttpClient()->getLastResponse());			
 		  			
    	}catch (Exception $e){
            if(strpos($e->getMessage(), 'simplexml') === false){
        		// developer mode
        		if(Mage::getIsDeveloperMode()){
        			
    	    		Mage::helper('epoqinterface/debug')
    	    			->log('Exception: '.$e->getMessage())
    		    		->log('Request URI: '.$this->getRestClient()->getUri()->getUri())
    		    		->log('Response: '.$this->getRestClient()->getHttpClient()->getLastResponse());		
    	    			   			
        			throw $e;
    
        		// error handling
        		}elseif($this->getIsErrorHandling()){
        			
        			$this->updateRequestFailureTime();
        			$this->updateFailureCount();	
        		}
            }
    		return null;
    	}
	
    	// reset error handling
    	if($this->getIsErrorHandling() && $this->getFailureCount()){
    		$this->updateFailureCount(true);
    	}
    	
    	return $result;
    }
    
	/**
	 * return Zend Rest Client
	 *
	 * @return Zend_Rest_Client
	 */
	public function getRestClient(){

		if(!$this->_restClient instanceof Zend_Rest_Client) {
			
			$this->_restClient = new Zend_Rest_Client();
			$this->_restClient->getHttpClient()->setConfig(
				array(
					'timeout' => Mage::getStoreConfig(self::XML_TIMEOUT_PATH)
				)
			);
		}
		var_dump($this->_restClient->getHttpClient());
		
		return $this->_restClient;
	}   
    
    /**
     * update Request Failure Time
     *
     */
    public function updateRequestFailureTime(){
    	
    	Mage::app()->saveCache(time() + Mage::getStoreConfig(self::XML_IDLE_TIME_PATH) * 60, self::CACHE_REQUEST_FAILURE_TIME);  		    	
    }
    
    /**
     * get Request Failure Time
     *
     * @return int
     */
    public function getRequestFailureTime(){
    		
        return Mage::app()->loadCache(self::CACHE_REQUEST_FAILURE_TIME);	    	
    }    

    /**
     * update Failure Counter
     *
     * @param boolean $reset
     */
    public function updateFailureCount($reset=false)
    {
		
		$count = $reset ? 0 : $this->getFailureCount() + 1;	
		
		Mage::app()->saveCache($count, self::CACHE_REQUEST_FAILURE_COUNTER);  
    }


    /**
     * get Failure count
     *
     * @return int
     */
    public function getFailureCount()
    {
        return Mage::app()->loadCache(self::CACHE_REQUEST_FAILURE_COUNTER);
    }    
    
  	/**
  	 * get is Error Handling enabled
  	 *
  	 * @return boolean
  	 */
  	public function getIsErrorHandling(){
  		
  		return Mage::getStoreConfig(self::XML_ERROR_HANDLING_PATH) ? true : false;
  	}

	/**
	 * get Session
	 *
	 * @return Flagbit_EpoqInterface_Model_Session
	 */
	protected function getSession(){
		
		return Mage::getSingleton('epoqinterface/session');
	}
	
	
	
    protected function getParamsArray(){
    	
    	$variables = array(
    		'tenantId'		=> Mage::getStoreConfig(self::XML_TENANT_ID_PATH),
    		'sessionId'		=> Mage::getSingleton('core/session')->getSessionId(),    	
    	); 

    	return $variables;
    }  	
	
	
	public function getRestUrl(){
		
		return Mage::getStoreConfig(self::XML_REST_URL_PATH);
	}
	
	public function getTenantId(){
		
		return Mage::getStoreConfig(self::XML_TENANT_ID_PATH);
	}

	public function getSessionId(){
		
		return Mage::getSingleton('core/session')->getSessionId();
	}
	
}