<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G.
 */
class Soon_AdvancedCache_Model_Exception extends Soon_AdvancedCache_Model_Abstract {

    /**
     * Exception title
     * 
     * @var string
     */
    protected $_title;

    /**
     * Initialize resource model
     */
    protected function _construct() {
        $this->_init('advancedcache/exception');
    }

    /**
     * Get title of exception
     * 
     * @return string
     */
    public function getTitle() {
        if (null === $this->_title) {
            $exceptionTypes = $this->getCacheConfig()->getExceptionTypes();
            $this->_title = $exceptionTypes[$this->getItemType()] . " {$this->getItemId()}";
        }

        return $this->_title;
    }

    /**
     * Delete exception when parent item is deleted
     * 
     * @param Varien_Event_Observer $observer
     */
    public function deleteExceptionOnEvent(Varien_Event_Observer $observer) {
        $object = $observer->getEvent()->getDataObject();
        if (get_class($object) == 'Mage_Cms_Model_Page') {
            $collection = $this->getResourceCollection()
                    ->addFieldToFilter('item_type', 'cms_page')
                    ->addFieldToFilter('item_id', $object->getIdentifier())
                    ->walk('delete');
        }
        
        return $this;
    }

    /**
     * Processing object before save data
     *
     * @return Soon_AdvancedCache_Model_Exception
     */
    protected function _beforeSave() {
        if (!$this->getExceptionId()) {
            $this->isObjectNew(true);
        }

        // Check if exception is unique
        $existingExceptions = $this->getResourceCollection()
                ->addFieldToFilter('item_type', $this->getItemType())
                ->addFieldToFilter('item_id', $this->getItemId())
                ->load();
        if ($existingExceptions->count() > 0 && $existingExceptions->getFirstItem()->getExceptionId() != $this->getExceptionId()) {
            $message = Mage::helper('advancedcache')->__('Exception for this item already exists');
            Mage::throwException($message);
        }

        return $this;
    }

}
