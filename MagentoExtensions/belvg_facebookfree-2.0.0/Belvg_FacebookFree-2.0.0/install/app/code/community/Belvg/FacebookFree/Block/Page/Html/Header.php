<?php

class Belvg_FacebookFree_Block_Page_Html_Header extends Mage_Page_Block_Html_Header
{
    public function _construct()
    {
        parent::_construct();
        $this->addData(array(
                'cache_lifetime' => 0,
        ));
    }

    public function getWelcome()
    {
        if (empty($this->_data['welcome'])) {
            if (Mage::isInstalled() && Mage::getSingleton('customer/session')->isLoggedIn()) {
                $this->_data['welcome'] = $this->__('Welcome, %s!', $this->escapeHtml(Mage::getSingleton('customer/session')->getCustomer()->getName()));
                if ($this->helper('facebookfree')->isActive() && $user_id = $this->helper('facebookfree')->getFbUserId()) {
                    $this->_data['welcome'] .= ' <img src="https://graph.facebook.com/' . $user_id . '/picture" height="20"/>';
                }
            } else {
                $this->_data['welcome'] = Mage::getStoreConfig('design/header/welcome');
            }
        }

        return $this->_data['welcome'];
    }

}
