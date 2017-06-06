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
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */
 
class MageWorx_Adminhtml_Customercredit_CreditController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('customer/manage');
    }
    
    protected function _initCustomer($idFieldName = 'id')
    {
        $customerId = (int) $this->getRequest()->getParam($idFieldName);
        $customerModel = Mage::getModel('customer/customer');
        if ($customerId)
        {
            $customerModel->load($customerId);
        }

        if (!$customerModel->getId()) {
            Mage::getSingleton('adminhtml/session')->addError($this->_helper()->__('The customer not found'));
        }

        Mage::register('current_customer', $customerModel);
        return $this;
    }
    
    public function indexAction()
    {
        $this->_initCustomer();
        $this->loadLayout()
            ->renderLayout();
    }
    
    public function logGridAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mageworx/customercredit_customer_edit_tab_customercredit_log_grid')->toHtml()
        );
    }
    
    /**
     * 
     * @return MageWorx_CustomerCredit_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('customercredit');
    }
}