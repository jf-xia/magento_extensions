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
class MDN_AdminLogger_Model_Observer extends Mage_Core_Model_Abstract
{
	
	/**
	 * ***********************************************************************************************************************************
	 * ***********************************************************************************************************************************
	 * TRIGGERS
	 * ***********************************************************************************************************************************
	 * ***********************************************************************************************************************************
	 */
	
	/**
	 * Method called each time an object is saved (and changed :)
	 *
	 */
	public function model_save_after(Varien_Event_Observer $observer)
	{

		$object = $observer->getEvent()->getObject(); 
				
		$objectType = mage::helper('AdminLogger')->getObjectType($object);
		if ($objectType)
		{
			$objectId = $object->getId();
			$actionType = mage::helper('AdminLogger')->getActionType($object);
			$objectDescription = mage::helper('AdminLogger')->getObjectDescription($object);
			$actionDescription = mage::helper('AdminLogger')->getActionDescription($object, $actionType);
			
			$log = true;
			if ($actionType == MDN_AdminLogger_Model_Log::kActionTypeUpdate && $actionDescription == '')
				$log = false;
			
			if ($log)
				mage::helper('AdminLogger')->addLog($actionType, 
													$objectType, 
													$objectId, 
													$objectDescription, 
													$actionDescription);
		}
	}

	/**
	 * called before an entity is saved (to know if it is being created)
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function model_save_before(Varien_Event_Observer $observer)
	{
		$object = $observer->getEvent()->getObject(); 
		$objectType = mage::helper('AdminLogger')->getObjectType($object);
		
		if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
			mage::log('Save before for '.$objectType);
		
		if (!$object->getId())
			$object->setis_new(true);
		
		if (mage::getStoreConfig('adminlogger/general/force_initial_data') == 1)
			$this->forceOrigDataLoad($object);

	}
	
	/**
	 * called before an entity is saved (to know if it is being created)
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function model_delete_after(Varien_Event_Observer $observer)
	{		
		$object = $observer->getEvent()->getObject(); 
		
		$objectType = mage::helper('AdminLogger')->getObjectType($object);
		if ($objectType)
		{
			$objectId = $object->getId();
			$actionType = MDN_AdminLogger_Model_Log::kActionTypeDelete ;
			$objectDescription = mage::helper('AdminLogger')->getObjectDescription($object);
			$actionDescription = '';
			
			mage::helper('AdminLogger')->addLog($actionType, 
												$objectType, 
												$objectId, 
												$objectDescription, 
												$actionDescription);
		}
	}
	
	/**
	 * LOg user authentication
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function admin_user_authenticate_after(Varien_Event_Observer $observer)
	{
		$object = $observer->getEvent(); 
		$user = $object->getuser();
		if ($user->getId())		
			mage::helper('AdminLogger')->addLog(MDN_AdminLogger_Model_Log::kActionTypeLogin, 
												'user', 
												$user->getId(), 
												mage::helper('AdminLogger')->__('User ').$user->getusername(), 
												mage::helper('AdminLogger')->__('Logged in at ').date('Y-m-d H:i'), 
												'system');		
	}
	
	/**
	 * User login failed
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function admin_session_user_login_failed(Varien_Event_Observer $observer)
	{
		$object = $observer->getEvent(); 
		$user = $object->getuser_name();
		mage::helper('AdminLogger')->addLog(MDN_AdminLogger_Model_Log::kActionTypeLogin, 
											'user', 
											0, 
											'User '.$user, 
											'Login failed at '.date('Y-m-d H:i'), 
											'system');				
	}
	
	public function catalog_product_website_update(Varien_Event_Observer $observer)
	{
		
	}

	public function catalog_category_change_products(Varien_Event_Observer $observer)
	{
		$object = $observer->getEvent(); 
		$category = $object->getcategory();
		$productIds = $object->getproduct_ids();

		mage::helper('AdminLogger')->addLog(MDN_AdminLogger_Model_Log::kActionTypeUpdate,
											'Category', 
											$category->getId(), 
											'Category '.$category->getName(), 
											'Products change to : '.join(',', $productIds));				
	}

	/**
	 * Fill in orig data array 
	 *
	 * @param unknown_type $object
	 * @return unknown
	 */
	private function forceOrigDataLoad($object)
	{
		$objectType = mage::helper('AdminLogger')->getObjectType($object);

		try 
		{
			//register ignored fields for optimization
		    if (!Mage::registry('adminlogger_force_orig_data')) 
		    {
		    	if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
					mage::log('Load force orig data in registry');
				$forceOrigData = mage::getStoreConfig('adminlogger/advanced/force_orig_data');
				$t_forceOrigData = explode("\n", $forceOrigData);		
	
				for($i=0;$i<count($t_forceOrigData);$i++)
					$t_forceOrigData[$i] = trim($t_forceOrigData[$i]);
				
				Mage::register('adminlogger_force_orig_data', $t_forceOrigData);
	        }
	        
	                
	       	//check if we have to load orig data
	       	if (($objectType != '') && in_array($objectType, Mage::registry('adminlogger_force_orig_data')))
			{
				if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
					mage::log('Force Orig Data for '.$objectType);
				$newObject = mage::getModel($objectType)->load($object->getId());
				foreach($newObject->getData() as $key => $value)
					$object->setOrigData($key, $value);					
			}
			else 
			{
				if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
					mage::log('Do NOT force Orig Data for '.$objectType);
			}
			
		}
		catch (Exception $ex)
		{
			if (mage::getStoreConfig('adminlogger/general/enable_log') == 1)
				mage::log('Erreur dans AdminLogger: '.$ex->getMessage().' - '.$ex->getTraceAsString());			
		}
		
		return $object;	
	}
	
	/**
	 * Method called by cron to prune logs
	 *
	 */
	public function handlerPruneLogs()
	{
		if (mage::getStoreConfig('adminlogger/general/auto_prune') == 1)
		{
			$pruneDelay = mage::getStoreConfig('adminlogger/general/auto_prune_delay');
			Mage::getResourceModel('AdminLogger/Log')->Prune($pruneDelay);    	    	
		}
	}
	
}