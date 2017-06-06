<?php

/**
 * Checklist Block Mysql4
 *
 * @category	Brisign
 * @package		Brisign_Checklist
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Checklist_Model_Mysql4_Checknote extends Mage_Core_Model_Mysql4_Abstract
{    
    protected function _construct()
    {
        $this->_init('checklist/checknote', 'checklist_checknote_id');
    }
}