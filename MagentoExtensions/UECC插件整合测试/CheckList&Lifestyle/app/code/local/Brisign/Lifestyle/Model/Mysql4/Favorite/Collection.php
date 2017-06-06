<?php

/**
 * Lifestyle Block Mysql4
 *
    protected $_selectedColumns = array(
        'lifestyle_favorite_id'   	=> 'lifestyle_favorite_id',
        'customer_id'               => 'customer_id',
        'title'                     => 'title',
        'url'                  		=> 'url',
    );
 * @category	Brisign
 * @package		Brisign_Lifestyle
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Lifestyle_Model_Mysql4_Favorite_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{    
	
    public function _construct()
    {
        parent::_construct();
        $this->_init('lifestyle/favorite');
    }

    protected function _initSelect()
    {
        parent::_initSelect();
        $select = $this->getSelect();
		//$select->where("lifestyle_favorite_id > 0");
        return $this;
    }

    public function addCustomerFilter($customer)
    {
        $this->getSelect()->where('customer_id = ?', $customer);
        return $this;
    }
}
