<?php
class Swatches_CustomOptions_Helper_Data extends Mage_Core_Helper_Data
{
    protected $_width;
    protected $_height;
    
    public function getSelectOptionTypes()
    {
        return array(
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN,
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO,
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX,
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE,
        );
    }
    
    public function getAllowedExtensions()
    {
        return array('jpg', 'jpeg', 'gif', 'png');
    }
    
    public function getImagePath($path = '')
    {
        return Mage::getBaseDir('media') . DS . 'swatches' . 
                DS . 'customoptions' . DS . $path;
    }
    
    public function getImageUrl($path = '')
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)
            . 'swatches/customoptions/' . $path;
    }
    
    public function getResizedUrl($image)
    {
        if (!$this->getWidth() OR !$this->getHeight()) {
            return $this->getImageUrl($image);
        }
        
        $resized  = $this->getResizedFolder();
        $origImage = $this->getImagePath($image);
        $newImage = $this->getImagePath($resized . DS . $image);
        if (!file_exists($newImage)) {
            if (!file_exists($this->getImagePath($resized))) {
                @mkdir($this->getImagePath($resized), 0777);
            }
            try {
                $imageObj = new Varien_Image($this->getImagePath($image));
                $imageObj->constrainOnly(true);
                $imageObj->keepFrame(false);
                $imageObj->keepTransparency(true);
                $imageObj->resize($this->getWidth(), $this->getHeight());
                $imageObj->save($newImage);
            }
            catch (Exception $e) {
                //Mage::log($e->getMessage(), null, 'swatches.log');
            }
        }
        return $this->getImageUrl($resized . '/' . $image);
    }
    
    protected function getWidth()
    {
        if (!$this->_width) {
            $this->_width = Mage::getStoreConfig('swatches/customoptions/width');
        }
        return $this->_width;
    }
    
    protected function getHeight()
    {
        if (!$this->_height) {
            $this->_height = Mage::getStoreConfig('swatches/customoptions/height');
        }
        return $this->_height;
    }
    
    protected function getResizedFolder()
    {
        return $this->getWidth() . 'x' . $this->getHeight();
    }
}
