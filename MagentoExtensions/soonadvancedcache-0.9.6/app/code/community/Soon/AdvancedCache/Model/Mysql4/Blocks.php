<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G. ()
 */
class Soon_AdvancedCache_Model_Mysql4_Blocks extends Mage_Core_Model_Mysql4_Abstract {

    /**
     * Initialize resource model
     */    
    protected function _construct() {
        $this->_init('advancedcache/blocks', 'block_id');
        $this->_blocksTable = Mage::getSingleton('core/resource')->getTableName('advancedcache/blocks');
        $this->_read = $this->_getReadAdapter();
    }
    
    /**
     * Load block from DB by identifier
     *
     * @param string $subscriberEmail
     * @return array
     */
    public function loadByIdentifier($identifier)
    {
        $select = $this->_read->select()
            ->from($this->_blocksTable)
            ->where('identifier=?',$identifier);

        $result = $this->_read->fetchRow($select);

        if(!$result) {
            return array();
        }

        return $result;
    }    
    
    /**
     * Load block from DB by block class
     *
     * @param string $subscriberEmail
     * @return array
     */
    public function loadByBlockClass($blockClass)
    {
        $select = $this->_read->select()
            ->from($this->_blocksTable)
            ->where('block_class=?',$blockClass);

        $result = $this->_read->fetchRow($select);

        if(!$result) {
            return array();
        }

        return $result;
    }    

}