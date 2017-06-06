<?php

class Wee_Fpc_Adminhtml_FpcController extends Mage_Adminhtml_Controller_Action
{
    public function cleanAction()
    {
        try {
            $session = Mage::getSingleton('adminhtml/session');
            $isCachedEnabled = Mage::helper('wee_fpc')->isEnabled();
            if ($isCachedEnabled) {
                Mage::getModel('wee_fpc/fullpagecache')->cleanCache();
                $session->addSuccess(
                    Mage::helper('wee_fpc')->__('The full page cache has been cleaned.')
                );
            }
        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('wee_fpc')->__('An error occurred while clearing the full page cache.')
            );
        }
        $this->_redirect('*/cache/index');
    }
}
