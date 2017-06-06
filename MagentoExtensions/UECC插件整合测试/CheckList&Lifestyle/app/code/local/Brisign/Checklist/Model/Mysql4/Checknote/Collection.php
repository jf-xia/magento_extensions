<?php

/**
 * Checklist Block Mysql4
 *
    protected $_selectedColumns = array(
        'checklist_checknote_id'   	=> 'checklist_checknote_id',
        'customer_id'               => 'customer_id',
        'title'                     => 'title',
        'url'                  		=> 'url',
    );
 * @category	Brisign
 * @package		Brisign_Checklist
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Checklist_Model_Mysql4_Checknote_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{    
	
    public function _construct()
    {
        parent::_construct();
        $this->_init('checklist/checknote');
    }

    protected function _initSelect()
    {
        parent::_initSelect();
        $select = $this->getSelect();
		//$select->where("checklist_checknote_id > 0");
        return $this;
    }

    public function addCustomerFilter($customer)
    {
        $this->getSelect()->where('customer_id = ?', $customer);
        return $this;
    }
}
