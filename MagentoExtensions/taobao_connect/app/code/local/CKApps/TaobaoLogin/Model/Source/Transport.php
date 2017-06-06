<?php
/**
 *
 * Copyright CKApps.com
 * email: app@ckapps.com
 *
 */

class CKApps_TaobaoLogin_Model_Source_Transport
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'https', 'label' => Mage::helper('sinalogin')->__('https')),
            array('value' => 'http', 'label' => Mage::helper('sinalogin')->__('http')),
        );
    }
}