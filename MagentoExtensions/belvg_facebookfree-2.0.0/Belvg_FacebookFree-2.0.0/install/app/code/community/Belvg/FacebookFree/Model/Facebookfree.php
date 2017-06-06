<?php

class Belvg_FacebookFree_Model_FacebookFree extends Mage_Core_Model_Abstract
{

    /**
     * Event prefix for observer
     *
     * @var string
     */
    protected $_eventPrefix = 'facebookfree';

    /**
     * init model
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('facebookfree/facebookfree');
    }

    /**
     * Check if customer was already logged in with FB before
     *
     * @param array $fb_data
     * @return boolean
     */
    public function checkFbCustomer(array $fb_data)
    {
        $this->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
        $collection = $this->getCollection();
        $collection->addFieldToFilter('fb_id', $fb_data['id'])
                ->addFieldToFilter('website_id', $this->getWebsiteId());
        if ($collection->count() && $customer_id = $collection->getFirstItem()->getCustomerId()) {
            return $customer_id;
        }

        return FALSE;
    }

    /**
     * Load data to the entity
     *
     * @param Belvg_FacebookFree_Model_Customer $customer
     * @return Belvg_FacebookFree_Model_FacebookFree
     */
    public function prepareData(Belvg_FacebookFree_Model_Customer $customer)
    {
        $data = array(
                'customer_id'   => (int)$customer->getId(),
                'website_id'    => (int)$customer->getWebsiteId(),
                'store_id'      => (int)$customer->getStoreId(),
                'fb_id'         => (int)$customer->getFbData('id'),
        );

        $this->setData($data);
        return $this;
    }

}