<?php
/** 
 * @category    Mana
 * @package     ManaPro_FilterSuperSlider
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/**
 * @author Mana Team
 *
 */
class ManaPro_FilterSuperSlider_Resource_Decimal extends ManaPro_FilterSlider_Resource_Decimal {
    public function getExistingValues($filter) {
        $select     = $this->_getSelect($filter);
        $adapter    = $this->_getReadAdapter();

        $rangeExpr  = new Zend_Db_Expr("decimal_index.value");
        $select->columns(array('value' => 'decimal_index.value'));
        $select->group('value');
        $select->order('value');

        // MANA BEGIN: make sure price filter is not applied
        $select->reset(Zend_Db_Select::WHERE);
        // MANA END

        return $adapter->fetchCol($select);
    }
}