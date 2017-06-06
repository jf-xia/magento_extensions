<?php
class Rack_Selfdelete_OrderController extends Mage_Core_Controller_Front_Action {


    public function cancelAction() 
    {
        if (!$this->_getCustomerSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        $_secretkey = $this->getRequest()->getParam("key");
        $_sessionkey = Mage::getSingleton('selfdelete/session')->getSecretKey();

        if ($_secretkey !== $_sessionkey) {
            $this->_getSession()->addError(Mage::helper('selfdelete')->__('Invalid access. Please retry.'));
            $this->_redirect('sales/order/history/');
        } else {
            $_orderId = $this->getRequest()->getParam("order_id");
            if (!$_order = Mage::getModel('sales/order')->load($_orderId)) {
              $this->_getSession()->addError(Mage::helper('selfdelete')->__('Requested order data does not exists.'));
              $this->_redirect('sales/order/history/');
            } else {
                Mage::register('isSecureArea', true);
                if($_order->canCancel() && $_order->cancel()->save()) {
                    $this->_getSession()->addSuccess($this->__('Order #%s has been successfully canceled.', $_order->getRealOrderId()));
                    Mage::getSingleton('selfdelete/session')->setSecretKey("");
                    $this->_redirect('sales/order/history/');
                } else {
                    $this->_getSession()->addError(Mage::helper('selfdelete')->__('Unable to cancel your order'));
                    $this->_redirect('sales/order/history/');
                }
            }
        }
        return;
    }

    protected function _getSession()
    {
        return Mage::getSingleton('selfdelete/session');
    }

    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        $action = $this->getRequest()->getActionName();
        if (!preg_match('/^(cancel|success)/i', $action)) {
            if (!$this->_getCustomerSession()->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
            }
        } else {
            $this->_getCustomerSession()->setNoReferer(true);
        }
    }

    public function postDispatch()
    {
        parent::postDispatch();
        $this->_getCustomerSession()->unsNoReferer(false);
    }
}
