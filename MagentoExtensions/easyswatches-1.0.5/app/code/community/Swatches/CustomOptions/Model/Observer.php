<?php
class Swatches_CustomOptions_Model_Observer
{
    public function addImagetoResult(Varien_Event_Observer $observer)
    {
        $collection = $observer->getEvent()->getCollection();
        if ($collection instanceof Mage_Catalog_Model_Resource_Product_Option_Value_Collection 
                OR $collection instanceof Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Option_Value_Collection) {
            if ($collection->count()) {
                $storeId = Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID;
                $swatches = Mage::getModel('swatches_customoptions/product_option_image')
                    ->getCollection()
                    ->addFieldToFilter('option_type_id', array('in' => $collection->getAllIds()))
                    ->addFieldToFilter('store_id', $storeId)
                ;
                foreach ($swatches as $item) {
                    $collection->getItemById($item->getOptionTypeId())
                        ->setImage($item->getImage());
                }              
            }
        }
        return $this;
    }
}
