<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAdmin
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

/**
 * Injects session related behavior
 * @author Mana Team
 *
 */
class ManaPro_FilterAdmin_Model_Session_Observer {
	const REMEMBER = 1;
	const RESTORE = 2;
	const REMOVE = 3;
	
	/* BASED ON SNIPPET: Models/Event handler */
	/**
	 * Remembers category filters or applies remembered filters (handles event "controller_action_predispatch_catalog_category_view")
	 * @param Varien_Event_Observer $observer
	 */
	public function rememberCategoryFilters($observer) {
		/* @var $action Mage_Catalog_CategoryController */ $action = $observer->getEvent()->getControllerAction();

		if (Mage::getStoreConfigFlag('mana_filters/session/save_applied_filters')) {
			if ($categoryId = (int) $action->getRequest()->getParam('id', false)) {
			    Mage::register('current_category', Mage::getModel('catalog/category')->setStoreId(Mage::app()->getStore()->getId())->load($categoryId));
				switch ($this->_getLayeredNavigationSessionAction($action)) {
					case self::REMOVE:
						Mage::getSingleton('core/session')->unsetData('m_category_filter_url_'.$categoryId);
						break;
					case self::REMEMBER:
						$query = array();
						foreach ($this->_getSpecialParameters() as $param) {
							$query[$param] = null;
						}
							$params = array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query);
							Mage::getSingleton('core/session')->setData('m_category_filter_url_'.$categoryId, 
								Mage::getUrl('*/*/*', $params));
						break;
					case self::RESTORE:
						if (($url = Mage::getSingleton('core/session')->getData('m_category_filter_url_'.$categoryId))
							&& $url != Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true))) 
						{
							// redirect to URL with applied filters
							$action->getResponse()->setRedirect($url);
							$action->getRequest()->setDispatched(true);
						}
						break;
					default:
						throw new Exception('Not implemented');
				}
				Mage::unregister('current_category');
			}
		}
	}
	
	protected static $_specialParameters;
	protected function _getSpecialParameters() {
		if (!self::$_specialParameters) {
			self::$_specialParameters = array(
            	Mage::getBlockSingleton('catalog/product_list_toolbar')->getPageVarName(),
            	Mage::getBlockSingleton('catalog/product_list_toolbar')->getLimitVarName(),
            	Mage::getBlockSingleton('catalog/product_list_toolbar')->getOrderVarName(),
            	Mage::getBlockSingleton('catalog/product_list_toolbar')->getDirectionVarName(),
            	Mage::getBlockSingleton('catalog/product_list_toolbar')->getModeVarName(),
            	'm-ajax',
            	'm-layered',
			);
		}
		return self::$_specialParameters;
	}
	/**
	 * Returns true if layered navigation filters should be saved, returns false if layered navigation 
	 * filters should be restored 
	 * @param Mage_Catalog_CategoryController $action
	 * @return int
	 */
	protected function _getLayeredNavigationSessionAction($action) {
		$count = 0;
		foreach (array_keys($action->getRequest()->getQuery()) as $param) {
			if (in_array($param, $this->_getSpecialParameters())) {
				$count++;
			}
		}
		if ($count > 0) {
			if (count($action->getRequest()->getQuery()) > 0) {
				return self::REMEMBER;
			}
			else {
				return self::REMOVE;
			}
		}
		else {
			return self::RESTORE;
		}
	}
	
}