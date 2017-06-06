<?php
class Swatches_CustomOptions_Block_Adminhtml_Catalog_Product_Edit_Tab_Swatches
    extends Mage_Adminhtml_Block_Widget 
        implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_optionsCollection;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('swatches/customoptions/catalog/product/edit/swatches.phtml');
    }
    
    protected function _getProduct()
    {
        return Mage::registry('current_product');
    }
    
    public function getTabLabel()
    {
        return $this->__('Custom Options Images');
    }
    
    public function getTabTitle()
    {
        return $this->__('Custom Options Images');
    }
    
    public function canShowTab()
    {
        if ($this->_getProduct()->getHasOptions() && $this->getOptionsCollection()->count()) {
            return true;
        }
        return false;
    }
    
    public function isHidden()
    {
        return ($this->getRequest()->getParam('store') != 0) ? true : false;
    }
    
    public function getSubmitUrl()
    {
        $urlData = array('id' => $this->_getProduct()->getId());
        return Mage::helper('adminhtml')
            ->getUrl('*/swatches_customoptions/upload', $urlData);
    }
    
    public function getUploadImagesButtonHtml()
    {
        return $this->getButtonHtml(
            $this->__('Upload Images'), 
            '$(\'swatches-customoptions-upload\').submit();', 
            'scalable save', 
            'upload_images_btn_customoptions'
        );
    }
    
    public function getOptionsCollection()
    {
        if (is_null($this->_optionsCollection)) {
            $this->_optionsCollection = Mage::getModel('catalog/product_option')
                ->getProductOptionCollection($this->_getProduct());
            foreach ($this->_optionsCollection as $key => $item) {
                if (!in_array($item->getType(), $this->_getSelectOptionTypes())) {
                    $this->_optionsCollection->removeItemByKey($key);
                }       
            }
        }
        return $this->_optionsCollection;
    }
    
    protected function _getSelectOptionTypes()
    {
        return Mage::helper('swatches_customoptions')->getSelectOptionTypes();
    }
}
