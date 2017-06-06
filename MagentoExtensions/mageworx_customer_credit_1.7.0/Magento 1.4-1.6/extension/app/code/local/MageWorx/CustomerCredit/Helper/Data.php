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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2012 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team
 */

class MageWorx_CustomerCredit_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isEnabled() {
        return Mage::getStoreConfigFlag('mageworx_customers/customercredit_credit/enable_credit');
    }
	
    public function isEnabledCodes() {
        return Mage::getStoreConfigFlag('mageworx_customers/customercredit_credit/enable_recharge_codes');
    }

    public function isEnabledPartialPayment() {
        return Mage::getStoreConfigFlag('mageworx_customers/customercredit_credit/enable_partial_credit_payment');
    }
    
    public function isEnabledCreditMemoReturn() {
        return Mage::getStoreConfigFlag('mageworx_customers/customercredit_credit/enable_credit_memo_return');
    }
    
    public function isDisplayCreditBlockAtCart() {
        return Mage::getStoreConfigFlag('mageworx_customers/customercredit_credit/display_credit_block_at_cart');
    }
        
    public function isEnabledCreditColumnsInGridOrderViewTabs() {
        return Mage::getStoreConfigFlag('mageworx_customers/customercredit_credit/enable_credit_columns_in_grid_order_view_tabs');
    }
    
    
    public function isEnabledCustomerBalanceGridColumn() {
        return Mage::getStoreConfigFlag('mageworx_customers/customercredit_credit/enable_customer_balance_grid_column');
    }
    
    public function isSendNotificationBalanceChanged() {
        return Mage::getStoreConfigFlag('mageworx_customers/customercredit_credit/send_notification_balance_changed');
    }
    
    public function getCreditTotals() {
        return explode(',', Mage::getStoreConfig('mageworx_customers/customercredit_credit/credit_totals'));
    }
    
    public function getDefaultQtyCreditUnits() {
        return Mage::getStoreConfig('mageworx_customers/customercredit_credit/default_qty_credit_units');        
    }
    
    public function getCreditProductSku() {
        return Mage::getStoreConfig('mageworx_customers/customercredit_credit/credit_product');        
    }    
        
    public function getJsCurrency() {
        $websiteCollection = Mage::getSingleton('adminhtml/system_store')->getWebsiteCollection();
        $currencyList = array();
        foreach ($websiteCollection as $website)
        {
            $currencyList[$website->getId()] = $website->getBaseCurrencyCode();
        }
        return Zend_Json::encode($currencyList);
    }
    
    // return:
    // -1 - no balabce checkbox
    // 0 - no balance radio
    // 1 - checkbox (partial payment)
    // 2 - radio (full payment)
    public function isPartialPayment($quote, $customerId = null, $websiteId = null) {
        if(!$customerId){
            if($customer = Mage::getSingleton('customer/session')){
                $customerId = $customer->getEntityId();
            } else {
                return false;
            }
        }

        if (!$websiteId) $websiteId = Mage::app()->getStore()->getWebsiteId();        

        $value = $this->getCreditValue($customerId, $websiteId);
        
        $isEnabledPartialPayment = $this->isEnabledPartialPayment();
        
        if ($value==0) {
            if ($isEnabledPartialPayment) return -1; else return 0;
        }        
                        
        if (Mage::app()->getStore()->isAdmin()) {
            $allItems = $quote->getAllItems();
            $productIds = array();
            foreach ($allItems as $item) {
                $productIds[] = $item->getProductId();
            }
        } else {
            $productIds = Mage::getSingleton('checkout/cart')->getProductIds();            
        }
        
        $addressType = Mage_Sales_Model_Quote_Address::TYPE_BILLING;
        $creditProductSku = $this->getCreditProductSku();
        foreach ($productIds as $productId) {
            $product = Mage::getModel('catalog/product')->load($productId);
            if (!$product) continue;
            // is credit product - no credit!
            if ($creditProductSku && $product->getSku()==$creditProductSku) return 0;
            
            $productTypeId = $product->getTypeId();
            if ($productTypeId!='downloadable' && $productTypeId!='virtual') {
                $addressType = Mage_Sales_Model_Quote_Address::TYPE_SHIPPING;
                break;
            }
        }
        
        //shipping or billing
        if ($addressType==Mage_Sales_Model_Quote_Address::TYPE_SHIPPING) {
            $address = $quote->getShippingAddress();
        } else {
            $address = $quote->getBillingAddress();
        }
        
        
        $subtotal = floatval($address->getBaseSubtotalWithDiscount()); //$address->getBaseSubtotal();
        $shipping = floatval($address->getBaseShippingAmount() - $address->getBaseShippingTaxAmount());
        $tax = floatval($address->getBaseTaxAmount());
        
        $grandTotal = $tail = floatval($quote->getBaseGrandTotal());
        if ($grandTotal==0) $grandTotal = $tail = floatval(array_sum($address->getAllBaseTotalAmounts()));
        if ($grandTotal==0) $grandTotal = $tail = $subtotal + $shipping + $tax;
        //echo $subtotal.'|'.$shipping.'|'.$tax.'|='.$grandTotal.'<br/>';
        
        $amount = 0;
        $creditTotals = $this->getCreditTotals();
        
        foreach ($creditTotals as $field) {
            switch ($field) {
                case 'subtotal':                            
                    $amount += $subtotal;
                    $tail -= $subtotal;
                    break;
                case 'shipping':
                    $amount += $shipping;
                    $tail -= $shipping;                    
                    break;
                case 'tax':
                    $amount += $tax;
                    $tail -= $tax;
                    break;                       
            }
        }
        
        $amount = round($amount, 2);
        $tail = round($tail, 2);        
        //echo $amount.'|'.$tail.'|'.$value; exit;
        
        if ($value >= $amount && $tail==0) {
            return 2;
        } else {
            if ($isEnabledPartialPayment) return 1; else return 0;
        }
    }
    
    public function sendNotificationBalanceChangedEmail($customer)
    {
        if (!version_compare(Mage::getVersion(), '1.5.0', '>=')) return $this->sendNotificationBalanceChangedEmailOld($customer);        
        $storeId = $customer->getStoreId();

        // Retrieve corresponding email template id and customer name        
        $templateId = 'customercredit_email_credit_changed_template';        
        $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
        
        $creditData = $customer->getCustomerCreditData();
        if (isset($creditData['value_change'])) $valueChange = intval($creditData['value_change']); else $valueChange = 0;
        if ($valueChange==0) return $this;
        
        if (isset($creditData['credit_value'])) $creditValue = intval($creditData['credit_value']); else $creditValue = 0;
        $balance = Mage::helper('core')->currencyByStore($creditValue + $valueChange, $storeId, true, false);
        
        if (isset($creditData['comment'])) $comment = trim($creditData['comment']); else $comment = '';        
        
        
        
        $mailer = Mage::getModel('core/email_template_mailer');        
        
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($customer->getEmail(), $customerName);            
        $mailer->addEmailInfo($emailInfo);

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig('sales_email/order_comment/identity', $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(array(
                'balance'   => $balance,                
                'customerName' => $customerName,
                'comment' => $comment                
            )
        );                
        
        $mailer->send();

        return $this;
    }        
    
    public function sendNotificationBalanceChangedEmailOld($customer)
    {
        // set design parameters, required for email (remember current)
        $currentDesign = Mage::getDesign()->setAllGetOld(array(
            'store'   => $customer->getStoreId(),
            'area'    => 'frontend',
            'package' => Mage::getStoreConfig('design/package/name', $customer->getStoreId()),
        ));

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $sendTo = array();

        $mailTemplate = Mage::getModel('core/email_template');
        
        $template = 'customercredit_email_credit_changed_template';
        $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
        
        $creditData = $customer->getCustomerCreditData();
        if (isset($creditData['value_change'])) $valueChange = intval($creditData['value_change']); else $valueChange = 0;
        if ($valueChange==0) return $this;
        
        if (isset($creditData['credit_value'])) $creditValue = intval($creditData['credit_value']); else $creditValue = 0;        
        $balance = Mage::helper('core')->currency($creditValue + $valueChange, true, false);        
        
        if (isset($creditData['comment'])) $comment = trim($creditData['comment']); else $comment = '';                

        $sendTo[] = array(
            'name'  => $customerName,
            'email' => $customer->getEmail()
        );        

        foreach ($sendTo as $recipient) {
            $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store' => $customer->getStoreId()))
                ->sendTransactional(
                    $template,
                    Mage::getStoreConfig('sales_email/order_comment/identity', $customer->getStoreId()),
                    $recipient['email'],
                    $recipient['name'],
                    array(
                        'balance'   => $balance,                
                        'customerName' => $customerName,
                        'comment' => $comment 
                    )
                );
        }

        $translate->setTranslateInline(true);

        // revert current design
        Mage::getDesign()->setAllGetOld($currentDesign);

        return $this;
    }
    
    public function createCreditProduct() {
        
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $tablePrefix = (string) Mage::getConfig()->getTablePrefix();                

        $attributeSetId = $connection->fetchOne("SELECT `default_attribute_set_id` FROM `".$tablePrefix."eav_entity_type` WHERE `entity_type_code` = 'catalog_product'");
        if (!$attributeSetId) return false;               
                
        $productData = array(
            'store_id' => 0,
            'attribute_set_id' => $attributeSetId,
            'type_id' => 'virtual',
            '_edit_mode' => 1,
            'name' => 'Credit Units',
            'sku' => 'customercredit',
            'website_ids' => array_keys(Mage::app()->getWebsites()),
            'status' => 1,
            'tax_class_id' => 0,
            'url_key' => '',
            'visibility' => 1,
            'news_from_date' => '',
            'news_to_date' => '',
            'is_imported' => 0,
            'price' => 1,
            'cost' => '',
            'special_price' => '',
            'special_from_date' => '',
            'special_to_date' => '',
            'enable_googlecheckout' => 1,
            'meta_title' => '',
            'meta_keyword' => '',
            'meta_description' => '', 
            'thumbnail' => 'no_selection',
            'small_image' => 'no_selection',
            'image' => 'no_selection',
            'media_gallery' => Array
                (
                    'images' => '[]',
                    'values' => '{"thumbnail":null,"small_image":null,"image":null}'
                ),

            'description' => 'This product is used to purchase credit units to fulfill internal balance.',
            'short_description' => 'This product is used to purchase credit units to fulfill internal balance.',
            'custom_design' => '',
            'custom_design_from' => '',
            'custom_design_to' => '',
            'custom_layout_update' => '', 
            'options_container' => 'container2',
            'page_layout' => '',
            'is_recurring' => 0,
            'recurring_profile' => '', 
            'use_config_gift_message_available' => 1,
            'stock_data' => Array
                (
                    'manage_stock' => 0,
                    'original_inventory_qty' => 0,
                    'qty' => 0,
                    'use_config_min_qty' => 1,
                    'use_config_min_sale_qty' => 1,
                    'use_config_max_sale_qty' => 1,
                    'is_qty_decimal' => 0,
                    'use_config_backorders' => 1,
                    'use_config_notify_stock_qty' => 1,
                    'use_config_enable_qty_increments' => 1,
                    'use_config_qty_increments' => 1,
                    'is_in_stock' => 0,
                    'use_config_manage_stock' => 0                                    
                ),
            'can_save_configurable_attributes' => false,
            'can_save_custom_options' => false,
            'can_save_bundle_selections' => false
        );
                
        try {
            $product = Mage::getModel('catalog/product')->setData($productData)->save();            
            $productId = $product->getId();
            if (version_compare(Mage::getVersion(), '1.5.0', '>=')) {
                Mage::getModel('catalogrule/rule')->applyAllRulesToProduct($productId);
            } else {    
                Mage::getModel('catalogrule/rule')->applyToProduct($productId);
            }
            return $productId;
        } catch (Exception $e) {
            return false;
        }    
    }
    
    public function getCreditProduct($fromConfig=false) {
        $sku = $this->getCreditProductSku();
        $productId = false;
        if (!$sku) {
            if ($fromConfig) return false;
            $sku = 'customercredit';            
        }    
        $storeId = Mage::app()->getStore()->getId();
        $productId = Mage::getModel('catalog/product')->setStoreId($storeId)->getIdBySku($sku);        
        if (!$productId) return false;
        return Mage::getModel('catalog/product')->setStoreId($storeId)->load($productId);        
    }    
    
    public function getCreditValue($customerId, $websiteId) {
        $creditValue = floatval(Mage::getModel('customercredit/credit')
                ->setCustomerId($customerId)
                ->setWebsiteId($websiteId)
                ->loadCredit()
                ->getValue());
        
        if (Mage::app()->getRequest()->getControllerName()=='sales_order_edit') {
            $orderId = Mage::getSingleton('adminhtml/session_quote')->getOrderId();
            $orderBaseCustomerCreditAmount = floatval(Mage::getModel('sales/order')->load($orderId)->getBaseCustomerCreditAmount());
            if ($orderBaseCustomerCreditAmount) {
                $creditValue += $orderBaseCustomerCreditAmount;
                Mage::getSingleton('adminhtml/session_quote')->setUseInternalCredit(true);
            }    
        }
        
        return $creditValue;
    }
    
}