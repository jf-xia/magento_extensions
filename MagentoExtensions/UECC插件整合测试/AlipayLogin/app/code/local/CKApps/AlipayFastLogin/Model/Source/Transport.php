<?php
/**
 *
 * Copyright CKApps.com
 * email: app@ckapps.com
 *
 */

class CKApps_AlipayFastLogin_Model_Source_Transport
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'https', 'label' => Mage::helper('alipayfastlogin')->__('https')),
            array('value' => 'http', 'label' => Mage::helper('alipayfastlogin')->__('http')),
        );
    }
}