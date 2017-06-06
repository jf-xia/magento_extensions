<?php

class Belvg_Facebookfree_Block_Init extends Mage_Core_Block_Template
{

    /**
     * Return applicaton Id
     *
     * @return string
     */
    public function getAppId()
    {
        return Mage::helper('facebookfree')->getAppId();
    }

    /**
     * Return encoded current URL for after auth redirection
     *
     * @return string
     */
    public function getLoginUrl()
    {
        return Mage::helper('facebookfree')->getLoginUrl();
    }

    /**
     * Get current FB locale according to the selected store locale
     *
     * @return string
     */
    public function getLocale()
    {
        return Mage::getModel('facebookfree/locale')->getLocale();
    }

}