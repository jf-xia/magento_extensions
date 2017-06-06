<?php
    class Topbuy_Addons_Model_Mysql4_Csgroup extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("addons/csgroup", "idcsgroup");
            $this->_isPkAutoIncrement = false;
        }
    
        public function getAddonsCollection($pIdProduct){
    //        $sourceTable = $this->getTable('addons/csgroup');
    //        $csproductmap  = $this->getTable('addons/csproductmap');
    //        $adapter     = $this->_getWriteAdapter();
    //        $adapter->beginTransaction();
    //        try {
    //            $select = $adapter->select();
    //            $columns = array(
    //                'csgroupname'            => 'tb_csgroup.csgroupname'
    //            );
    //            $select->from(array('source_table' => $sourceTable), $columns)->joinInner(
    //                    array('csproductmap' => $csproductmap),
    //                    'source_table.idcsgroup = csproductmap.idcsgroup',
    //                    array()
    //                );
    ////                    ->joinInner(
    ////                    array('csproductmap'=>$csproductmap), 
    ////                    'main_table.idcsgroup = csproductmap.idcsgroup',
    ////                    array('csproductmap.*'));
    ////                     AND '
    ////                        . $adapter->quoteInto('csproductmap.idproduct = ?', $pIdProduct)
    //        } catch (Exception $e) {
    //            $adapter->rollBack();
    //            throw $e;
    //        }
    //
    //        $adapter->commit();
    //        return $this;
            $read= Mage::getSingleton('core/resource')->getConnection('core_read');  
            $csgroup = $read->fetchAll("SELECT tb_csgroup.csgroupname, tb_csproductmap.idproduct, tb_csproductmap.sortby FROM tb_csgroup Inner Join tb_csproductmap ON tb_csproductmap.idcsgroup = tb_csgroup.idcsgroup WHERE tb_csproductmap.idproduct =  ? ORDER BY tb_csproductmap.sortby ASC;",$pIdProduct);   
            return $csgroup;
        }   
    }
	 