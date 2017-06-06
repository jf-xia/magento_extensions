<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2009 Maison du Logiciel (http://www.maisondulogiciel.com)
 * @author : Olivier ZIMMERMANN
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MDN_AdminLogger_Helper_Data extends Mage_Core_Helper_Abstract
{

	/**
	 * Insert log
	 *
	 */
	public function addLog($actionType, $objectType, $objectId, $objectDescription, $description, $forceUser = false)
	{
		if (mage::getStoreConfig('adminlogger/general/enable') != 1)
			return;
		
		if ($forceUser)
			$userName = $forceUser;
		else
			$userName = $this->getCurrentUserName();
			
		if ($userName)
		{
			mage::getModel('AdminLogger/Log')
					->setal_date(date('Y-m-d H:i:s'))
					->setal_user($userName)
					->setal_object_id($objectId)
					->setal_object_description($objectDescription)
					->setal_description($description)
					->setal_action_type($actionType)
					->setal_object_type($objectType)
					->save();
		}
	}
	
	/**
	 * Return name for current user
	 *
	 */
	public function getCurrentUserName()
	{
		$retour = null;
		try 
		{
			if (Mage::getSingleton('admin/session')->getUser())
				 $retour = Mage::getSingleton('admin/session')->getUser()->getusername();			
		}
		catch (Exception $ex)
		{
			//nothing
		}
		return $retour;
	}
	
	/**
	 * Return object type
	 *
	 * @param unknown_type $object
	 */
	public function getObjectType($object)
	{
		$retour = '';
		$resourceName = $object->getResourceName();
		$resourceName = strtolower($resourceName);
		
		if ($this->considerObjectType($resourceName))		
			return strtolower($resourceName);
		else 
			return null;
	}
	
	/**
	 * Return action type
	 *
	 * @param unknown_type $object
	 */
	public function getActionType($object)
	{
		$retour = '';
		
		if ($object->getis_new())
			$retour = MDN_AdminLogger_Model_Log::kActionTypeInsert ;
		else
			$retour = MDN_AdminLogger_Model_Log::kActionTypeUpdate ;
		
		return $retour;
	}
	
	/**
	 * return object description
	 *
	 * @param unknown_type $object
	 */
	public function getObjectDescription($object)
	{
		$retour = '';
		$objectType = $this->getObjectType($object);
		
		switch ($objectType)
		{
			case 'customer/customer':
				$retour = mage::helper('AdminLogger')->__('Customer %s (id %s)', $object->getName(), $object->getId());				
				break;
			case 'catalog/product':
				$retour = mage::helper('AdminLogger')->__('Product %s (id %s)', $object->getName(), $object->getId());				
				break;
			case 'catalog/category':
				$retour = mage::helper('AdminLogger')->__('Category %s (id %s)', $object->getName(), $object->getId());				
				break;				
			case 'tax/class':
				$retour = mage::helper('AdminLogger')->__('Tax class %s (id %s)', $object->getclass_name(), $object->getId());				
				break;
			case 'adminlogger/log':
				//nothing
				break;
			case 'customer/address':
				$retour = mage::helper('AdminLogger')->__('Address for customer %s (id %s)', $object->getCustomer()->getName(), $object->getId());				
				break;
			case 'cataloginventory/stock_item':
				$retour = mage::helper('AdminLogger')->__('Stock for product %s (id %s)', $object->getProductName(), $object->getId());								
				break;
			case 'customer/group':
				$retour = mage::helper('AdminLogger')->__('Customer group %s (id %s)', $object->getcustomer_group_code(), $object->getId());												
				break;
			case 'productreturn/rma':
				$retour = mage::helper('AdminLogger')->__('Product Return %s (id %s)', $object->getrma_ref(), $object->getId());												
				break;
			case 'checkout/agreement':
				$retour = mage::helper('AdminLogger')->__('Agreement %s (id %s)', $object->getName(), $object->getId());												
				break;
			case 'sales/order':
				$retour = mage::helper('AdminLogger')->__('Sales Order %s (id %s)', $object->getincrement_id(), $object->getId());												
				break;
			case 'catalogrule/rule':
				$retour = mage::helper('AdminLogger')->__('Catalog rule %s (id %s)', $object->getname(), $object->getId());												
				break;
			case 'salesrule/rule':
				$retour = mage::helper('AdminLogger')->__('Sales rule %s (id %s)', $object->getname(), $object->getId());												
				break;
			case 'purchase/manufacturer':
				$retour = mage::helper('AdminLogger')->__('Manufacturer %s (id %s)', $object->getman_name(), $object->getId());												
				break;
			case 'admin/user':
				$retour = mage::helper('AdminLogger')->__('User %s (id %s)', $object->getusername(), $object->getId());												
				break;				
			case 'cms/page':
				$retour = mage::helper('AdminLogger')->__('CMS Page %s (id %s)', $object->gettitle(), $object->getId());												
				break;				
			case 'cms/block':
				$retour = mage::helper('AdminLogger')->__('CMS Block %s (id %s)', $object->gettitle(), $object->getId());												
				break;				
			default :
				$retour = mage::helper('AdminLogger')->__($objectType.' (id %s)', $object->getId());	
				if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)											
					mage::log('Unable to find description for type '.$objectType);
				break;
		}
		
		return $retour;
	}

	/**
	 * Return action description depending of actionType
	 *
	 * @param unknown_type $object
	 * @param unknown_type $actionType
	 * @return unknown
	 */
	public function getActionDescription($object, $actionType)
	{
		$retour = '';
		/**
                 * modif text  
                 */
		switch ($actionType)
		{
			case MDN_AdminLogger_Model_Log::kActionTypeInsert :
				if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
					mage::log('Retrieve description for insert');
				//nothing
				break;
			case MDN_AdminLogger_Model_Log::kActionTypeDelete :
				if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
					mage::log('Retrieve description for delete');
				//nothing				
				break;
			case MDN_AdminLogger_Model_Log::kActionTypeUpdate :
				if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
					mage::log('Retrieve description for update');
				$data = $object->getData();
				$origData = $object->getOrigData();
                                
                                $retour ='changes: ';
                                
				if ($data && $origData)
				{
					foreach($origData as $key => $value)
					{
						if ($this->considerField($key))
						{
							$newValue = '';
							if (isset($data[$key]))
								$newValue = $data[$key];
							$oldValue = $value;
							$retour .= $this->compareForUpdate($key, $oldValue, $newValue);
						}
					}
				}
				else 
				{
					if (!$data)
					{
						if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
							mage::log('Data is null');
					}
					if (!$origData)
					{
						if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
							mage::log('Orig Data is null');
					}
				}
				
				if ($retour == '')
				{
					if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
						mage::log('Unable to find changes for '.$actionType.' for object '.$this->getObjectType($object));
				}

				break;
		}
		
		return $retour;
	}
	
	/**
	 * Compare datas to return changed items description
	 *
	 * @param unknown_type $key
	 * @param unknown_type $oldValue
	 * @param unknown_type $newValue
	 * @return unknown
	 */
	private function compareForUpdate($key, $oldValue, $newValue)
	{
		$retour = '';

		//object comparison
		if (is_object($oldValue) && is_object($newValue))
		{		
			$oldData = $oldValue->getData();
			$newData = $newValue->getData();
			
			foreach($newData as $key => $value)
			{
				if (isset($oldData[$key]))
					$retour .= $this->compareForUpdate($key, $oldData[$key], $newData[$key]);
				else 
					$retour .= $this->compareForUpdate($key, '', $newData[$key]);				
			}
			return $retour;
			
		}
		
		//array comparison
		$compared = false;
		if (is_array($oldValue) && is_array($newValue))
		{
			if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
				mage::log('#Compare Array');
			foreach($newValue as $key => $value)
			{
				if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
					mage::log('Compare key '.$key);
				if (isset($oldValue[$key]))
					$retour .= $this->compareForUpdate($key, $oldValue[$key], $newValue[$key]);
				else 
					$retour .= $this->compareForUpdate($key, '', $newValue[$key]);				
			}
			return $retour;
		}
		
		//simple type comparison
		$logField = true;
		if (is_array($oldValue))
			$logField = false;
		if (is_array($newValue))
			$logField = false;
		if ((($oldValue == '') && ($newValue == null)) || (($oldValue == null) && ($newValue == '')))
			$logField = false;
		if (is_numeric($oldValue) && is_numeric($newValue))
		{
			$oldValue = floatval($oldValue);
			$newValue = floatval($newValue);
		}
		if ($oldValue == $newValue)
			$logField = false;
		if ($logField) 
                        if( $newValue != ''){
                            $retour .= '<a href="#" class="lien-popup"><b>'.$key.'</b><span><b>Old value : </b>'.$oldValue.'<br /><b>New value : </b>'.$newValue.'</span></a> , ';	
                        }            
                            
              return $retour;
	}
	
	/**
	 * Function to know if we log field information or not
	 *
	 * @param unknown_type $fieldName
	 */
	public function considerField($fieldName)
	{
		//register ignored fields for optimization
	    if (!Mage::registry('adminlogger_fields_to_ignore')) 
	    {
	    	if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
				mage::log('Load ignored fields in registry');
			$ignoredFields = mage::getStoreConfig('adminlogger/advanced/fields_to_ignore');
			$t_ignoredFields = explode("\n", $ignoredFields);		

			for($i=0;$i<count($t_ignoredFields);$i++)
				$t_ignoredFields[$i] = trim($t_ignoredFields[$i]);
			
			Mage::register('adminlogger_fields_to_ignore', $t_ignoredFields);
        }
        
        //check if field is managed
		if (in_array($fieldName, Mage::registry('adminlogger_fields_to_ignore')))
		{
			if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
				mage::log('Field '.$fieldName.' ignored ');
			return false;
		}
		else 
		{
			if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
				mage::log('Field '.$fieldName.' considered ');
			return true;
		}		
        
	}

	/**
	 * Check if we log changes for entity type
	 *
	 * @param unknown_type $objectType
	 * @return unknown
	 */
	public function considerObjectType($objectType)
	{
		//register object types for optimization
	    if (!Mage::registry('adminlogger_ignored_object_types')) 
	    {
	    	if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
				mage::log('Load ignored object types in registry');
			$ignoredObjectTypes = mage::getStoreConfig('adminlogger/advanced/object_to_ignore');
			$t_ignoredObjectTypes = explode("\n", $ignoredObjectTypes);		

			for($i=0;$i<count($t_ignoredObjectTypes);$i++)
				$t_ignoredObjectTypes[$i] = trim($t_ignoredObjectTypes[$i]);
			
			Mage::register('adminlogger_ignored_object_types', $t_ignoredObjectTypes);
        }

        //check if object type is managed
		if (in_array($objectType, Mage::registry('adminlogger_ignored_object_types')))
		{
			if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
				mage::log('Object type '.$objectType.' ignored ');
			return false;
		}
		else 
		{
			if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
					mage::log('Object type '.$objectType.' considered ');
			return true;
		}		
	}


}

?>