<?php

/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.0
 *
 */

class EcommerceTeam_EasyCheckout_Block_Onepage_Shipping_Method extends Mage_Checkout_Block_Onepage_Abstract
{
    public function isShow()
    {
        return !$this->getQuote()->isVirtual();
    }
}
