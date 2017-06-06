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
class MDN_AdminLogger_Model_Log extends Mage_Core_Model_Abstract
{
	const kActionTypeInsert = 'insert';
	const kActionTypeUpdate = 'update';
	const kActionTypeDelete = 'delete';
	const kActionTypeMiscellaneous = 'misc';
	const kActionTypeLogin = 'login';
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('AdminLogger/Log');
	}	
	
	/**
	 * Return an array with action types
	 *
	 */
	public function getActionTypes()
	{
		$retour = array();

		$retour[self::kActionTypeInsert ] = mage::helper('AdminLogger')->__(self::kActionTypeInsert );
		$retour[self::kActionTypeUpdate ] = mage::helper('AdminLogger')->__(self::kActionTypeUpdate );
		$retour[self::kActionTypeDelete ] = mage::helper('AdminLogger')->__(self::kActionTypeDelete);
		$retour[self::kActionTypeMiscellaneous ] = mage::helper('AdminLogger')->__(self::kActionTypeMiscellaneous );
		$retour[self::kActionTypeLogin] = mage::helper('AdminLogger')->__(self::kActionTypeLogin );
		
		return $retour;	
	}

}