<?php
class PWS_ProductQA_Model_Mysql4_Productqa extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {       
        $this->_init('pws_productqa/productqa','productqa_id');
    } 
    
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getCreatedOn()) {
            $object->setCreatedOn($this->formatDate(time()));
        }
        
        $object->setUpdatedOn($this->formatDate(time()));
        parent::_beforeSave($object);
    }
    
    
    
    
    
}

