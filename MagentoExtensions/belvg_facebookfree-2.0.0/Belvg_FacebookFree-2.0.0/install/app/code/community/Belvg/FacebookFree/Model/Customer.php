<?php

class Belvg_FacebookFree_Model_Customer extends Mage_Customer_Model_Customer
{

    /**
     * Array of the FB user profile information
     * @var array
     */
    private $_fb_data = array();

    /**
     * Assign FB data to the entity
     *
     * @param array $fb_data
     * @return Belvg_FacebookFree_Model_Customer
     */
    public function setFbData(array $fb_data)
    {
        $this->_fb_data = $fb_data;
        return $this;
    }

    /**
     * Get data from the entity
     *
     * @param string|NULL $key
     * @return type
     */
    public function getFbData($key = NULL)
    {
        $data = $this->_fb_data;

        if (!is_null($key) && isset($data[$key])) {
            $data = $data[$key];
        }

        return $data;
    }

    /**
     * Check if customer exists
     *
     * @return boolean
     */
    public function checkCustomer()
    {
        $this->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
        $this->setStoreId(Mage::app()->getStore()->getStoreId());

        $this->loadByEmail($this->getFbData('email'));

        if ($this->getId()) {
            return $this->getId();
        }

        return FALSE;
    }

    /**
     * Check if customer was already logged in with FB before
     *
     * @return boolean
     */
    public function checkFbCustomer()
    {
        return Mage::getModel('facebookfree/facebookfree')->checkFbCustomer($this->getFbData());
    }

    /**
     * Map FB data to the entity
     *
     * @return Belvg_FacebookFree_Model_Customer
     */
    public function prepareData()
    {
        $this->setData('firstname', $this->getFbData('first_name'));
        $this->setData('lastname', $this->getFbData('last_name'));
        $this->setData('email', $this->getFbData('email'));
        $this->setData('password', $this->generatePassword());
        $this->setData('is_active', TRUE);
        $this->setData('confirmation', NULL);
        return $this;
    }

}