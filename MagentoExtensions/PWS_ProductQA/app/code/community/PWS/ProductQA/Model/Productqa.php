<?php
class PWS_ProductQA_Model_Productqa extends Mage_Core_Model_Abstract 
{
    protected function _construct()
    {
        $this->_init('pws_productqa/productqa');
    }
    
    
    public function loadExtra($id)
    {
        $collection = $this->getCollection()
                            ->joinProducts()
                            ->joinStore()
                            ->addFieldToFilter('productqa_id', $id);
        
        if ($collection->getSize()) {
            return $collection->getFirstItem();
        }  
        
        return false;
    }
    
    
}
