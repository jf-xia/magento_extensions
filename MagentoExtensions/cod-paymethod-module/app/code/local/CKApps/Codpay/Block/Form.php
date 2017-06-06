<?php
/**
 *
 * Copyright CKApps.com
 * email: app@ckapps.com
 *
 */
class CKApps_Codpay_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        $this->setTemplate('codpay/form.phtml');
        parent::_construct();
    }
}