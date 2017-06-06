<?php

class Belvg_FacebookFree_CustomerController extends Mage_Core_Controller_Front_Action
{

    /**
     * Login FB user
     *
     * @return void
     */
    public function loginAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        try {
            $fb_data = Mage::getModel('facebookfree/request')->load();
            $customer_model = Mage::getModel('facebookfree/customer')->setFbData($fb_data);

            // check if we need to create customer or register FB user
            if (!$customer_id = $customer_model->checkFbCustomer()) {
                if (!$customer_id = $customer_model->checkCustomer()) {
                    // create customer
                    $customer_id = $this->createCustomer($customer_model);
                }

                // create record about FB user
                Mage::getModel('facebookfree/facebookfree')->prepareData($customer_model)->save();
            }

            // login customer
            $this->_getSession()->loginById($customer_id);
            $this->_loginPostRedirect();
        } catch (Exception $e) {
            Mage::log($e->getMessage(), NULL, 'facebookfree.log');
            $this->_getSession()->addError(Mage::helper('facebookfree')->__('Facebook Connect attempt failed'));
            $this->_redirect('customer/account/login');
        }
    }

    /**
     * Trigger customer save process
     *
     * @param Belvg_FacebookFree_Model_Customer $customer_model
     * @return int
     */
    private function createCustomer(Belvg_FacebookFree_Model_Customer $customer_model)
    {
        $customer_model->prepareData()->save();

        Mage::dispatchEvent('customer_register_success', array('account_controller' => $this, 'customer' => $customer_model));

        $customer_model->setConfirmation(NULL)->save();
        $customer_model->sendNewAccountEmail();

        return $customer_model->getId();
    }

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Prepare to autoredirect customer after successful login
     *
     * @return void
     */
    private function _loginPostRedirect()
    {
        $session = $this->_getSession();
        if ($referer = $this->getRequest()->getParam(Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME)) {
            $referer = Mage::helper('core')->urlDecode($referer);
            if ((strpos($referer, Mage::app()->getStore()->getBaseUrl()) === 0)
                    || (strpos($referer, Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, TRUE)) === 0)) {
                $session->setBeforeAuthUrl($referer);
            } else {
                $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
            }
        } else {
            $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
        }
        
        $this->_redirectUrl($session->getBeforeAuthUrl(TRUE));
    }

}