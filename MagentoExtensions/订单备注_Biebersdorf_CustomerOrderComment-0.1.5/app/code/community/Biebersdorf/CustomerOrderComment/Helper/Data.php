<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Biebersdorf
 * @package    Biebersdorf_CustomerOrderComment
 * @copyright  Copyright (c) 2009 Ottmar Biebersdorf (http://www.obiebersdorf.de)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Biebersdorf_CustomerOrderComment_Helper_Data extends Mage_Core_Helper_Abstract
{
    /*
     * This method is called by an event when the Customer
     * places an Order in the Onepage-Checkout.
     */
    public function setCustomerOrderComment($observer)
    {
        /*
         * We added the textarea form-field "biebersdorfCustomerOrderComment"
         * in the template "checkout/onepage/agreements.phtml".
         */
        $orderComment = $this->_getRequest()->getPost('biebersdorfCustomerOrderComment');

        $orderComment = trim($orderComment);

        if ($orderComment != "")
        {
            $observer->getEvent()->getOrder()->setBiebersdorfCustomerordercomment($orderComment);
        }
    }
}
