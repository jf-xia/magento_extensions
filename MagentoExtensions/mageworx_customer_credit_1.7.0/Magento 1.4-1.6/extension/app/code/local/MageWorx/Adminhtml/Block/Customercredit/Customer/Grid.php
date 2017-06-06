<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2011 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team
 */
class MageWorx_Adminhtml_Block_Customercredit_Customer_Grid extends Mage_Adminhtml_Block_Customer_Grid
{   
    public function setCollection($collection)
    {                                
        if (Mage::helper('customercredit')->isEnabled() && Mage::helper('customercredit')->isEnabledCustomerBalanceGridColumn()) {        
            $fields = array();
            foreach (Mage::getConfig()->getFieldset('customer_account') as $code=>$node) {
                if ($node->is('name')) {
                    //$this->addAttributeToSelect($code);
                    $fields[$code] = $code;
                }
            }

            $collection->getSelect()->joinLeft(array('credit_tbl'=>$collection->getTable('customercredit/credit')),
                'credit_tbl.customer_id = e.entity_id'                    
                //array('credit_value' => new Zend_Db_Expr('IFNULL(credit_tbl.`value`, 0)'))
            );        
            $collection->addExpressionAttributeToSelect('credit_value', 'IFNULL(credit_tbl.`value`, 0)', $fields);
            //$sql = $collection->getSelect()->assemble();
            //$collection->getSelect()->reset()->from(array('e' => new Zend_Db_Expr('('.$sql.')')), '*');
        }    
        return parent::setCollection($collection);
    }
    

    protected function _prepareColumns()
    {        
        if (Mage::helper('customercredit')->isEnabled() && Mage::helper('customercredit')->isEnabledCustomerBalanceGridColumn()) {
            $currencyCode = $this->getCurrentCurrencyCode();
            $this->addColumnAfter('credit_value', array(            
                //'renderer'  => 'mageworx/tweaks_adminhtml_sales_order_grid_renderer_products',
                'type'  => 'currency',
                'currency_code' => $currencyCode,
                'header' => Mage::helper('customercredit')->__('Credit Balance'),
                'index' => 'credit_value',
                'width' => '100px',
                ), 'group');
        }    
        return parent::_prepareColumns();
    }
    
    public function getCurrentCurrencyCode()
    {
        if (is_null($this->_currentCurrencyCode)) {
            $this->_currentCurrencyCode = (count($this->_storeIds) > 0)
                ? Mage::app()->getStore(array_shift($this->_storeIds))->getBaseCurrencyCode()
                : Mage::app()->getStore()->getBaseCurrencyCode();
        }
        return $this->_currentCurrencyCode;
    }
}
