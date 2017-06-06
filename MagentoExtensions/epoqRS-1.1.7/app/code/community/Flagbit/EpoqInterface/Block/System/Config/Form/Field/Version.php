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


class Flagbit_EpoqInterface_Block_System_Config_Form_Field_Version extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	
	protected $versionUrl = 'http://epoq.flagbit.de/';
	
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
   	
    	$version = (string) Mage::getConfig()->getModuleConfig('Flagbit_EpoqInterface')->version;
    	$curVersion = $this->getCurrentVersion($version);
    	
    	$notice = '';
    	$icon = '';
    	if($curVersion !== null){
    		
	    	if(version_compare($version, $curVersion, '>=')){
	    		$icon = '<img src="'.$this->getSkinUrl('images/fam_bullet_success.gif').'" style="float: left; margin-right: 3px;"/>';
	    		$notice = $this->__('Up to date');
	    		
	    	}elseif(version_compare($version, $curVersion, '<')){
	    		$icon = '<img src="'.$this->getSkinUrl('images/error_msg_icon.gif').'" style="float: left; margin-right: 3px;"/>';
	    		$notice = $this->__('New Version %s ready for Download', $curVersion);
	    	}
    	}

        return $icon.$version. ($notice ? ' <strong>('.$notice.')</strong>' : '');
    }
    
    
    /**
     * Retrieve Last update time
     *
     * @return int
     */
    public function getLastUpdate()
    {
        return Mage::app()->loadCache('epoqinterface_version_lastcheck');
    }

    /**
     * Set last update time (now)
     *
     * @return Flagbit_EpoqInterface_Block_System_Config_Form_Field_Version
     */
    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'epoqinterface_version_lastcheck');
        return $this;
    }    
    
    /**
     * Retrieve current Version
     *
     * @return int
     */
    public function getCurrentVersion($version = '')
    {
    	$currentVersion = Mage::app()->loadCache('epoqinterface_version_current');
    	
        if($this->getLastUpdate() + (60 * 60 * 24) < time() or !$currentVersion){
			
        	try{
        	
		    	$client = new Zend_Http_Client;
		    	$result = $client->setUri($this->versionUrl)
		    					->setMethod(Zend_Http_Client::POST)
		    					->setConfig(
									array(
										'timeout' => 1
									)
								)
		    					->setParameterPost(
		    						array(
		    							'do'		=> 'versioncheck',
		    							'version'	=> $version
		    						)
		    					)
		    					->request();
		    				
		    	$this->setLastUpdate();
		    	
		    	$currentVersion = trim($result->getBody());
		    	Mage::app()->saveCache($currentVersion, 'epoqinterface_version_current');
	    	
        	}catch (Exception $e){
        		
        		return null;
        	}
    	}    	
    	
    	if(!preg_match('/([0-9]+)\.([0-9]+)\.([0-9]+)/', $currentVersion)){
    		return null;
    	}
    	
        return $currentVersion;
    }

}