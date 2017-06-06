<?php
if (version_compare(Mage::getVersion(), '1.6.0', '>')) {
    abstract class Swatches_CustomOptions_Model_Resource_Product_Option_Abstract
        extends Mage_Core_Model_Resource_Db_Abstract {};
}
else {
    abstract class Swatches_CustomOptions_Model_Resource_Product_Option_Abstract
        extends Mage_Core_Model_Mysql4_Abstract {};
}

class Swatches_CustomOptions_Model_Resource_Product_Option_Image 
    extends Swatches_CustomOptions_Model_Resource_Product_Option_Abstract
{
    protected function _construct()
    {
        $this->_init('swatches_customoptions/images', 'image_id');
    }
    
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeSave($object);
        
        $select = $this->_getWriteAdapter()->select()
            ->from($this->getMainTable())
            ->where('option_type_id = ?', (int)$object->getData('option_type_id'))
            ->where('store_id = ?', $object->getStoreId());
        
        $result = $this->_getWriteAdapter()->fetchAll($select);
        $imageFolder = Mage::helper('swatches_customoptions')->getImagePath();
        foreach($result as $record) {
            @unlink($imageFolder . $record['image']);
        }
        
        $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            array(
                'option_type_id = ?' => (int)$object->getData('option_type_id'),
                'store_id = ?' => $object->getStoreId(),
            )
        );
        return $this;
    }
    
    public function deleteImages($option_type_ids = array())
    {
        if ($option_type_ids) {
            $select = $this->_getWriteAdapter()->select()
                ->from($this->getMainTable())
                ->where('option_type_id IN (?)', $option_type_ids);

            $result = $this->_getWriteAdapter()->fetchAll($select);
            $imageFolder = Mage::helper('swatches_customoptions')->getImagePath();
            foreach($result as $record) {
                @unlink($imageFolder . $record['image']);
            }
   
            $this->_getWriteAdapter()->delete(
                $this->getMainTable(),
                array('option_type_id IN (?)' => $option_type_ids)
            );
        }
        return $this;
    }
}
