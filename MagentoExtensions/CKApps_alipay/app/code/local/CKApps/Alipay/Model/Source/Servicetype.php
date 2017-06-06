<?php
/**
 *
 * Copyright CKApps.com
 * email: app@ckapps.com
 *
 */

class CKApps_Alipay_Model_Source_Servicetype
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'trade_create_by_buyer', 'label' => Mage::helper('alipay')->__('Standard double interface')),
            array('value' => 'create_direct_pay_by_user', 'label' => Mage::helper('alipay')->__('Immediately to the account')),
            array('value' => 'create_partner_trade_by_buyer', 'label' => Mage::helper('alipay')->__('Secured transaction')),
        );
    }
}



