<?php

/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */
class MageWorx_CustomerCredit_Model_Checkout_Type_Onepage extends Mage_Checkout_Model_Type_Onepage {

    public function savePayment($data) {

        if (!empty($data['use_internal_credit'])) {
//            $internalCredit = Mage::getModel('customercredit/credit')
//                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
//                                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
//                                ->loadCredit()
//                                ->getValue();

            Mage::getSingleton('checkout/session')->setUseInternalCredit(true);
        } else {

            Mage::getModel('checkout/session')->setUseInternalCredit(false);
        }

        return parent::savePayment($data);
    }

}