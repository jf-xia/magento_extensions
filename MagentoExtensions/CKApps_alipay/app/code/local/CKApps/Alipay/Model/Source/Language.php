<?php
/**
 *
 * Copyright CKApps.com
 * email: app@ckapps.com
 *
 */


class CKApps_Alipay_Model_Source_Language
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'EN', 'label' => Mage::helper('alipay')->__('English')),
            array('value' => 'FR', 'label' => Mage::helper('alipay')->__('French')),
            array('value' => 'DE', 'label' => Mage::helper('alipay')->__('German')),
            array('value' => 'IT', 'label' => Mage::helper('alipay')->__('Italian')),
            array('value' => 'ES', 'label' => Mage::helper('alipay')->__('Spain')),
            array('value' => 'NL', 'label' => Mage::helper('alipay')->__('Dutch')),
        );
    }
}



