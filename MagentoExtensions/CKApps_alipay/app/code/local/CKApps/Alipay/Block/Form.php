<?php
/**
 *
 * Copyright CKApps.com
 * email: app@ckapps.com
 *
 */
class CKApps_Alipay_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        $this->setTemplate('alipay/form.phtml');
        parent::_construct();
    }

}