<?php
/**
 * J2T RewardsPoint2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@j2t-design.com so we can send you a copy immediately.
 *
 * @category   Magento extension
 * @package    RewardsPoint2
 * @copyright  Copyright (c) 2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Rewardpoints_Model_Quote extends Mage_Sales_Model_Quote
{
    protected function _validateCouponCode()
    {
        $code = $this->_getData('coupon_code');
        if ($code) {
            $addressHasCoupon = false;
            $addresses = $this->getAllAddresses();
            if (count($addresses)>0) {
                foreach ($addresses as $address) {
                    //if ($address->hasCouponCode()) {
                    if (preg_match("/".$code."/i", $address->getCouponCode())) {
                        $addressHasCoupon = true;
                    }
                }
                if (!$addressHasCoupon) {
                    $this->setCouponCode('');
                }
            }
        }
        return $this;
    }
    
    
    public function isAllowedGuestCheckout()
    {
        if (Mage::getStoreConfig('rewardpoints/registration/referral_guestallow', $this->getStoreId()) && Mage::getSingleton('rewardpoints/session')->getReferralUser()){
            return false;
        }
        return Mage::helper('checkout')->isAllowedGuestCheckout($this, $this->getStoreId());
    }
}