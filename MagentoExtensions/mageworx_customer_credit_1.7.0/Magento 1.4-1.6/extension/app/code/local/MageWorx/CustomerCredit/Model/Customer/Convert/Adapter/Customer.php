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

class MageWorx_CustomerCredit_Model_Customer_Convert_Adapter_Customer extends Mage_Customer_Model_Convert_Adapter_Customer
{
    public function saveRow($importData)
    {
        parent::saveRow($importData);

        if (isset($importData['credit_balance'])) {

            $customer = $this->getCustomerModel();
            $website = $this->getWebsiteByCode($importData['website']);

            $customer->setWebsiteId($website->getId())
                    ->loadByEmail($importData['email']);

            $creditModel = Mage::getModel('customercredit/credit')
                    ->setCustomerId($customer->getEntityId())
                    ->setWebsiteId($customer->getWebsiteId())
                    ->loadCredit();
            if(empty($importData['credit_balance'])){
                return $this;
            }

            $operator = substr($importData['credit_balance'], 0, 1);
            if($operator != '-' || $operator += '+'){
                $operator = substr($importData['credit_balance'], -1);
            }
            
            if ($operator == '-' || $operator == '+') {
                $valueChange = (float)$importData['credit_balance'];
            } else {
                $valueChange = (float)$importData['credit_balance'] - $creditModel->getValue();
            }

            if ($valueChange != 0) {
                $creditModel->setValueChange($valueChange)
                        ->setActionType(0)
                        ->save();
            }

        }

        return $this;
    }
}