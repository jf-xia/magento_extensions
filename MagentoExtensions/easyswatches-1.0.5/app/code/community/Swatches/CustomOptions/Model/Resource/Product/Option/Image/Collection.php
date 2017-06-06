<?php
if (version_compare(Mage::getVersion(), '1.6.0', '>')) {
    abstract class Swatches_CustomOptions_Model_Resource_Product_Option_Collection_Abstract
        extends Mage_Core_Model_Resource_Db_Collection_Abstract {};
}
else {
    abstract class Swatches_CustomOptions_Model_Resource_Product_Option_Collection_Abstract
        extends Mage_Core_Model_Mysql4_Collection_Abstract {};
}

class Swatches_CustomOptions_Model_Resource_Product_Option_Image_Collection 
    extends Swatches_CustomOptions_Model_Resource_Product_Option_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('swatches_customoptions/product_option_image');
    }
}
