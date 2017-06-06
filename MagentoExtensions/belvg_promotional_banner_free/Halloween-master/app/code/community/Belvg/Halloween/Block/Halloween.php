<?php

/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
  /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 * *************************************** */
/* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
  /***************************************
 *         DISCLAIMER   *
 * *************************************** */
/* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 * ****************************************************
 * @category   Belvg
 * @package    Belvg_Halloween
 * @author Pavel Novitsky <pavel@belvg.com>
 * @copyright  Copyright (c) 2010 - 2013 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

/**
 * Promotion products list
 */
class Belvg_Halloween_Block_Halloween extends Mage_Core_Block_Template
{

    /**
     * Helper instance
     *
     * @var NULL|Belvg_Halloween_Helper_Data
     */
    protected static $_helper = NULL;

    /**
     * Product collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_productCollection = NULL;

    /**
     * Internal constructor, init helper instance, set cache params
     *
     */
    public function _construct()
    {
        // init helper
        if (is_null(self::$_helper)) {
            self::$_helper = Mage::helper('halloween');
        }

        // set cache
        $this->addData(array(
                'cache_lifetime' => 86400,
                'cache_tags' => array($this->_getHelper()->getCacheTag()),
                'cache_key' => implode('-', $this->_getHelper()->getSkus()),
        ));
        parent::_construct();
    }

    /**
     * Get helper instance
     *
     * @return Belvg_Halloween_Helper_Data
     */
    protected function _getHelper()
    {
        return self::$_helper;
    }

    /**
     * Get block position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->_getHelper()->getPosition();
    }

    /**
     * Block event type
     *
     * @return string
     */
    public function getEventType()
    {
        return $this->_getHelper()->getEventType();
    }

    /**
     * Retrieve loaded product collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }

    /**
     * Retrieve loaded product collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            // get product collection
            $collection = Mage::getResourceModel('catalog/product_collection');
            Mage::getModel('catalog/layer')->prepareProductCollection($collection);
            $collection->addStoreFilter();
            // add selected SKUs to the filter
            $collection->addAttributeToFilter('sku', array(
                    'in' => $this->_getHelper()->getSkus(),
            ));

            $this->_productCollection = $collection;
        }

        return $this->_productCollection;
    }

    /**
     * Check if block is allowed to show
     *
     * @return boolean
     */
    public function isAllowed()
    {
        return $this->_getHelper()->isAllowed();
    }

    /**
     * Get cooke nme for disabling block output
     *
     * @retur string;
     */
    public function getCookieName()
    {
        return $this->_getHelper()->getCookieName();
    }

    /**
     * Return expiry date of the cookie for hiding banner
     *
     * @return string
     */
    public function getExpires()
    {
        return $this->_getHelper()->getExpires();
    }

}
