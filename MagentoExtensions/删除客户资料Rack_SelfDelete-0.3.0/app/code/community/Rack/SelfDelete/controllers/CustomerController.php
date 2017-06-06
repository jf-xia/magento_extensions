<?php
class Rack_SelfDelete_CustomerController extends Mage_Core_Controller_Front_Action {

    public function indexAction() 
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function deletePostAction() 
    {
        if (!$this->_getCustomerSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        $_customer = Mage::getModel('customer/customer')->load($this->_getCustomerSession()->getId());
        $_post = $this->getRequest()->getPost();

        if (!$_customer->validatePassword($_post['password'])) {
            $this->_getSession()->addError(Mage::helper('selfdelete')->__('Password is incorrect.'));
            $this->_redirect('*/*/');
        } else {
            Mage::register('isSecureArea', true);
            if($_customer->delete()) {
                $this->_redirect('*/*/success');
            } else {
                $this->_getSession()->addError(Mage::helper('selfdelete')->__('Unable to delete your account'));
                $this->_redirect('*/*/');
            }
        }
        return;
    }
    
    public function successAction() 
    {
        $this->loadLayout();
        $this->renderLayout();
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
        if (!preg_match('/^(index|delete|success)/i', $action)) {
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
