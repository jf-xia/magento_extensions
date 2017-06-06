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
 * @copyright  Copyright (c) 2012 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team
 */

class MageWorx_CustomerCredit_IndexController extends Mage_Core_Controller_Front_Action {

    public function preDispatch() {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }

        if (!Mage::helper('customercredit')->isEnabled()) {
            $this->norouteAction();
            return;
        }
        return $this;
    }

    public function indexAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->loadLayoutUpdates();

        $data = Mage::getSingleton('customer/session')->getCustomercreditFormData(true);
        Mage::register('customercredit_code', new Varien_Object());
        if (!empty($data)) {
            Mage::registry('customercredit_code')->addData($data);
        }

        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('customercredit')->__('My Credit'));
        $this->renderLayout();
    }

    public function logAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->loadLayoutUpdates();
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('customercredit')->__('My Credit Activity'));
        if ($block = $this->getLayout()->getBlock('customer.account.link.back')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $this->renderLayout();
    }

    public function refillAction() {
        if (!Mage::helper('customercredit')->isEnabledCodes())
            $this->_forward('index');
        if ($this->getRequest()->has('customercredit_code')) {
            $code = $this->getRequest()->getPost('customercredit_code');
            try {
                $codeModel = Mage::getModel('customercredit/code')->loadByCode($code);
                $refillCredit = $codeModel->getCredit();
                $codeModel->useCode();

                Mage::getSingleton('customer/session')->addSuccess($this->__(
                                'Credit Balance was refilled with %s successfully using Recharge Code "%s".', Mage::helper('core')->currency($refillCredit), Mage::helper('core')->htmlEscape($codeModel->getCode()))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('customer/session')->setCustomercreditFormData($this->getRequest()->getPost())
                        ->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('customer/session')->addException($e, $this->__('Error occur while refilling the credit.'));
            }
            $this->_redirect('*');
        }
    }

    public function updateCreditPostAction() {
        Mage::getSingleton('checkout/session')->setUseInternalCredit(true);
        $this->_redirect('checkout/cart');
    }

    public function removeCreditUseAction() {
        Mage::getSingleton('checkout/session')->setUseInternalCredit(false);
        $this->_redirect('checkout/cart');
    }

}