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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
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
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_CustomerCredit_Model_Mysql4_Credit_Log_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    protected function _construct() {
        $this->_init('customercredit/credit_log');
    }

    /**
     * Filter collection by customer credit
     * 
     * @param int|array $id 
     * @return MageWorx_CustomerCredit_Model_Mysql4_Credit_Log_Collection
     */
    public function addCreditFilter($id) {
        $this->addFieldToFilter('credit_id', array('in' => $id));
        return $this;
    }

    /**
     * Filter collection by customers
     * 
     * @param int|array $id 
     * @return MageWorx_CustomerCredit_Model_Mysql4_Credit_Log_Collection
     */
    public function addCustomerFilter($id) {
        $this->addFieldToFilter('customer_id', array('in' => $id));
        return $this;
    }
    
    
    public function addOrderFilter($id) {
        $this->addFieldToFilter('order_id', $id);
        return $this;
    }
    
    public function addActionTypeFilter($type) {
        $this->addFieldToFilter('action_type', $type);
        return $this;
    }
    

    /**
     * Filter collection by websites
     * 
     * @param int|array $id 
     * @return MageWorx_CustomerCredit_Model_Mysql4_Credit_Log_Collection
     */
    public function addWebsiteFilter($id) {
        $this->addFieldToFilter('website_id', array('in' => $id));
        return $this;
    }

    protected function _initSelect() {
        parent::_initSelect();
        $this->getSelect()
                ->joinInner(array('credit' => $this->getTable('customercredit/credit')), 'main_table.credit_id = credit.credit_id', 
                    array('website_id' => 'credit.website_id', 'customer_id' => 'credit.customer_id')
                )
                ->order('log_id DESC');
        ;
        return $this;
    }

}