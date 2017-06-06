<?php

class Belvg_FacebookFree_Helper_Data extends Belvg_FacebookFree_Helper_Config
{

    /**
     * Return encoded current URL for after auth redirection
     *
     * @return string
     */
    public function getLoginUrl()
    {
        $referer = Mage::helper('core')->urlEncode(Mage::helper('core/url')->getCurrentUrl());
        return Mage::getUrl('facebookfree/customer/login', array('referer' => $referer));
    }

    /**
     *
     * @return int
     */
    public function getFbUserId()
    {
        $user_id = NULL;

        if ($data = Mage::getModel('facebookfree/request_cookie')->getParsedCookie()) {
            $user_id = $data['user_id'];
        }

        return $user_id;
    }

}