<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Productquestions
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */
class AW_Productquestions_Helper_Data extends Mage_Core_Helper_Abstract
{

    /*
     * Returns current product
     * @param bool $inspectRegistry Whether to check Magento registry for current product
     * @return Mage_Catalog_Model_Product Current product
     */
    public function getCurrentProduct($inspectRegistry = false)
    {
        if($inspectRegistry)
        {
            $product = Mage::registry('product');
            if(!($product instanceof Mage_Catalog_Model_Product))
                $product = Mage::registry('current_product');

            if($product instanceof Mage_Catalog_Model_Product)
                return $product;
        }

        $productId = (int) Mage::app()->getRequest()->getParam('id');
        if(!$productId) return $this->__('No product ID');

        $product = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($productId);

        if(!$product
        || !($product instanceof Mage_Catalog_Model_Product)
        ||  $productId != $product->getId()
        || !Mage::helper('catalog/product')->canShow($product)
        || !in_array(Mage::app()->getStore()->getWebsiteId(), $product->getWebsiteIds())
        )   return $this->__('No such product');

        return $product;
    }


    /*
     * Checks whether module output is disabled in admin section
     * @return bool Check result
     */
    public static function isModuleOutputDisabled()
    {
        return (bool) Mage::getStoreConfig('advanced/modules_disable_output/AW_Productquestions');
    }


    /*
     * Returns service message telling the customer that his/her registration is needed
     * @return string Message itself
     */
    public function getPleaseRegisterMessage()
    {
        return Mage::getStoreConfig('productquestions/question_form/please_register');
    }


    /*
     * Returns HTML containing links to product question page
     * @result string HTML of the block
     */
    public function getSummaryHtml()
    {
        return Mage::app()->getLayout()->createBlock('productquestions/summary')->toHtml();
    }


    /*
     * Checks whether current customer needs registration
     * @return bool Check result
     */
    public static function checkIfGuestsAllowed()
    {
        return      Mage::getStoreConfig('productquestions/question_form/guests_allowed')
                ||  Mage::getSingleton('customer/session')->isLoggedIn();
    }


    /*
     * Checks whether aheadWorks Advanced Newsletter extension is installed and active
     * @return bool Check result
     */
    public static function isAdvancedNewsletterInstalled()
    {
        $modules = (array)Mage::getConfig()->getNode('modules')->children();

        return      array_key_exists('AW_Advancednewsletter', $modules)
                &&  'true' == (string) $modules['AW_Advancednewsletter']->active;
    }


    /*
     * Returns current version of the Advanced Newsletter extension
     * @return int Version number
     */
    public static function getAdvancedNewsletterVersion()
    {
        if(!$anVersion = Mage::getConfig()->getModuleConfig('AW_Advancednewsletter')->version)
            return false;

        $parts = explode('.', $anVersion);
        while(count($parts) < 3) $parts[] = 0;
        $ver = 0;
        foreach($parts as $p) $ver = $ver*100 + $p;

        return $ver;
    }


    /*
     * Subscribes customer to Advanced Newsletter segments
     * @param string $email Customer email
     * @param string $name Customer name
     * @param array $segments Advanced Newsletter segments
     * @return null
     */
    public function subscribeAdvancedNewsletterSegment($email, $name, $segments)
    {
        if(!is_array($segments)) $segments = array($segments);

        $anVersion = self::getAdvancedNewsletterVersion();

        $anModel = Mage::getModel('advancednewsletter/subscriptions');

        if($anVersion < 10200) // 1.0 & 1.0.2
            foreach($segments as $segment)
                $anModel->subscribe( // public function subscribe($email, $firstname, $lastname, $segment)
                    $email,
                    '',     // $firstname
                    $name,  // $lastname
                    $segment);
        else  // 1.2.0 and above
            foreach($segments as $segment)
                $anModel->subscribe( // public function subscribe($email, $firstname, $lastname, $salutation, $phone, $segment)
                    $email,
                    '',     // $firstname
                    $name,  // $lastname
                    null,   // $salutation,
                    null,   // $phone,
                    $segment);
    }


    /*
     * Subscribes customer to newsletter
     * @param string $email Customer email
     * @return null
     */
    public function subscribeCustomer($email)
    {
        $subscriber = Mage::getModel('newsletter/subscriber');
        $session = Mage::getSingleton('core/session');

        try
        {
            $subscriber->subscribe($email);
            if($subscriber->getIsStatusChanged())
                $session->addSuccess($this->__('You have been subscribed to newsletters'));
        }
        catch (Exception $e) {
            $session->addException($e, $this->__('There was a problem with the newsletter subscription')
                            .($e instanceof Mage_Core_Exception) ? ': '.$e->getMessage() : '');
        }
    }


    /*
     * Replaces URLs found in text with the appropriate links
     * @param string $text A text to parse
     * @return string Processed text
     */
    public static function parseURLsIntoLinks($text)
    {
        if(!Mage::getStoreConfig('productquestions/interface/parse_urls_into_links'))
            return nl2br(htmlentities($text, null, 'UTF-8'));

        $parts = preg_split('#\s((?:https?|ftp)://\S+)\s#', ' '.$text.' ', -1, PREG_SPLIT_DELIM_CAPTURE);
        $isHref = true;
        $res = '';
        foreach($parts as $part)
            $res .= ($isHref = !$isHref)
                    ? '<a href="'.$part.'">'.$part.'</a>'
                    : ' '.nl2br(htmlentities($part, null, 'UTF-8')).' ';
        return $res;
    }

    public function getSender($storeId = null){

    $senderCode = Mage::getStoreConfig('productquestions/email/sender_email_identity',$storeId);
    $sender = array(
        'name' => Mage::getStoreConfig('trans_email/ident_'.$senderCode.'/name',$storeId),
        'mail' => Mage::getStoreConfig('trans_email/ident_'.$senderCode.'/email',$storeId),
        );

    return $sender;
    }

}
