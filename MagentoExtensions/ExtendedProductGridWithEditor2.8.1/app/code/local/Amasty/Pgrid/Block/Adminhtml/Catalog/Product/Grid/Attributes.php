<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Pgrid
*/
class Amasty_Pgrid_Block_Adminhtml_Catalog_Product_Grid_Attributes extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ampgrid/attributes.phtml');
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
    
    public function getAttributes()
    {
        $collection = Mage::getResourceModel('catalog/product_attribute_collection')
                         ->addVisibleFilter();
        $collection->getSelect()->where(
            $collection->getConnection()->quoteInto('main_table.frontend_input IN (?)', array('text', 'select', 'multiselect', 'boolean', 'textarea', 'price', 'weight'))
        );
        $collection->getSelect()->where(
            $collection->getConnection()->quoteInto('main_table.attribute_code NOT IN (?)', Mage::helper('ampgrid')->getDefaultColumns())
        );
        return $collection;
    }
    
    public function getSelectedAttributes()
    {
        return Mage::helper('ampgrid')->getGridAttributes();
    }
    
    public function getSaveUrl()
    {
        $url = $this->getUrl('ampgrid/adminhtml_attribute/save');
        if (Mage::getStoreConfig('web/secure/use_in_adminhtml'))
        {
            $url = str_replace(Mage::getStoreConfig('web/unsecure/base_url'), Mage::getStoreConfig('web/secure/base_url'), $url);
        }
        return $url;
    }
    
    public function getBackUrl()
    {
        return $this->helper('core/url')->getCurrentUrl();
    }
}