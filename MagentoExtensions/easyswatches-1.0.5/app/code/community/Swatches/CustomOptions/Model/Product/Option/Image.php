<?php
class Swatches_CustomOptions_Model_Product_Option_Image extends Mage_Core_Model_Abstract
{
    protected $_images = array();

    protected function _construct()
    {
        $this->_init('swatches_customoptions/product_option_image');
    }

    public function addImage($option_type_id, $image)
    {
        $this->_images[$option_type_id] = array(
            'option_type_id' => $option_type_id,
            'image'          => $image,
        );
        return $this;
    }

    public function getImages()
    {
        return $this->_images;
    }

    public function unsetValues()
    {
        $this->_images = array();
        return $this;
    }

    public function saveImages()
    {
        foreach ($this->getImages() as $option_type_id => $data) {
            $this->setData($data);
            $this->save();
        }
        return $this;
    }
    
    public function deleteImages($option_type_ids = array())
    {
        $this->getResource()->deleteImages($option_type_ids);
    }
}
