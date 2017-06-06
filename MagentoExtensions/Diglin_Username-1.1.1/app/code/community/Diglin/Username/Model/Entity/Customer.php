<?php
/**
 * Diglin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Diglin
 * @package     Diglin_Username
 * @copyright   Copyright (c) 2011 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Diglin_Username_Model_Entity_Customer extends Mage_Customer_Model_Entity_Customer{
	
    protected function _beforeSave(Varien_Object $customer)
    {
        parent::_beforeSave($customer);

        if ($customer->getSharingConfig()->isWebsiteScope()) {
            $websiteId = (int) $customer->getWebsiteId();
        }else{
            $websiteId = null;
        }
        
        $model = Mage::getModel('customer/customer');
        $result = $model->customerUsernameExists($customer->getUsername(), $websiteId);
        if ($result && $result->getId() != $customer->getId()) {
            throw Mage::exception('Mage_Core', Mage::helper('customer')->__("Username already exists"));
        }

        return $this;
    }
    
	protected function _getDefaultAttributes(){
		$attributes = parent::_getDefaultAttributes();
		array_push($attributes, 'is_active');
		return $attributes;
	}
	
	/**
     * Load customer by username
     *
     * @param Mage_Customer_Model_Customer $customer
     * @param string $username
     * @param bool $testOnly
     * @return Mage_Customer_Model_Entity_Customer
     * @throws Mage_Core_Exception
     */
    public function loadByUsername(Mage_Customer_Model_Customer $customer, $username, $testOnly = false)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getEntityTable(), array($this->getEntityIdField()))
            ->joinNatural(array('cev' => $this->getTable('customer_entity_varchar')))
            ->joinNatural(array('ea' => $this->getTable('eav_attribute')))
            ->where('ea.attribute_code=\'username\' AND cev.value=?',$username);
        if ($customer->getSharingConfig()->isWebsiteScope()) {
            if (!$customer->hasData('website_id')) {
                Mage::throwException(Mage::helper('customer')->__('Customer website ID must be specified when using the website scope.'));
            }
            $select->where('website_id=?', (int)$customer->getWebsiteId());
        }

        if ($id = $this->_getReadAdapter()->fetchOne($select, 'entity_id')) {
            $this->load($customer, $id);
        }
        else {
            $customer->setData(array());
        }
        return $this;
    }
}