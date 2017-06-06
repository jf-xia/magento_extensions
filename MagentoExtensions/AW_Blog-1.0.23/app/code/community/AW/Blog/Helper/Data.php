<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-L.txt
 *
 * @category   AW
 * @package    AW_Blog
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */
class AW_Blog_Helper_Data extends Mage_Core_Helper_Abstract {
    const XML_PATH_ENABLED = 'blog/blog/enabled';
    const XML_PATH_TITLE = 'blog/blog/title';
    const XML_PATH_MENU_LEFT = 'blog/blog/menuLeft';
    const XML_PATH_MENU_RIGHT = 'blog/blog/menuRoght';
    const XML_PATH_FOOTER_ENABLED = 'blog/blog/footerEnabled';
    const XML_PATH_LAYOUT = 'blog/blog/layout';

    public function isEnabled() {
        return Mage::getStoreConfig(self::XML_PATH_ENABLED);
    }

    public function isTitle() {
        return Mage::getStoreConfig(self::XML_PATH_TITLE);
    }

    public function isMenuLeft() {
        return Mage::getStoreConfig(self::XML_PATH_MENU_LEFT);
    }

    public function isMenuRight() {
        return Mage::getStoreConfig(self::XML_PATH_MENU_RIGHT);
    }

    public function isFooterEnabled() {
        return Mage::getStoreConfig(self::XML_PATH_FOOTER_ENABLED);
    }

    public function isLayout() {
        return Mage::getStoreConfig(self::XML_PATH_LAYOUT);
    }

    public function getUserName() {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim("{$customer->getFirstname()} {$customer->getLastname()}");
    }

    public function getRoute() {

        $this->_getActualStoreId();

        $route = Mage::getStoreConfig('blog/blog/route');
        if (!$route) {
            $route = "blog";
        }
        return $route;
    }

    private function _getActualStoreId() {

        $params = Mage::app()->getRequest()->getParams();

        if (isset($params['___from_store'])) {

            $storeName = $params['___from_store'];
            $pathInfo = Mage::app()->getRequest()->getPathInfo();
            $path = explode("/", preg_replace("#^/#is", "", $pathInfo),2); 
 
            $router = $path[0];
            $stores = Mage::app()->getStore()->getCollection();
            
            foreach ($stores as $store) {
                if ($store->getCode() == $storeName) {
                    $blogRouter = Mage::getStoreConfig('blog/blog/route', $store->getId());
                    if ($blogRouter == $router) {
                        $droute = Mage::getStoreConfig('blog/blog/route', Mage::app()->getStore()->getId());
                        Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl() . $droute . '/' . $path[1]);
                    }
                }
            }

            return false;
        }
        return false;
    }

    public function getEnabled() {
        return Mage::getStoreConfig('blog/blog/enabled') && $this->extensionEnabled('AW_Blog');
    }

    public function getUserEmail() {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }

    /*
     * Recursively searches and replaces all occurrences of search in subject values replaced with the given replace value
     * @param string $search The value being searched for
     * @param string $replace The replacement value
     * @param array $subject Subject for being searched and replaced on
     * @return array Array with processed values
     */

    public function recursiveReplace($search, $replace, $subject) {
        if (!is_array($subject))
            return $subject;

        foreach ($subject as $key => $value)
            if (is_string($value))
                $subject[$key] = str_replace($search, $replace, $value);
            elseif (is_array($value))
                $subject[$key] = self::recursiveReplace($search, $replace, $value);

        return $subject;
    }

    public function extensionEnabled($extension_name) {
        $modules = (array) Mage::getConfig()->getNode('modules')->children();
        if (!isset($modules[$extension_name])
                || $modules[$extension_name]->descend('active')->asArray() == 'false'
                || Mage::getStoreConfig('advanced/modules_disable_output/' . $extension_name)
        )
            return false;
        return true;
    }

    public function addRss($head, $path) {
        if ($head instanceof Mage_Page_Block_Html_Head)
            $head->addItem("rss", $path, 'title="' . Mage::getStoreConfig(self::XML_PATH_TITLE) . '"');
    }

    public function getRssEnabled() {
        return (Mage::getStoreConfigFlag('blog/rss/enable') && Mage::getStoreConfigFlag('rss/config/active'));
    }

    public function convertSlashes($tag, $direction = 'back') {

        if ($direction == 'forward') {
            $tag = preg_replace("#/#is", "&#47;", $tag);
            $tag = preg_replace("#\\\#is", "&#92;", $tag);
            return $tag;
        }

        $tag = str_replace("&#47;", "/", $tag);
        $tag = str_replace("&#92;", "\\", $tag);

        return $tag;
    }

    public function filterWYS($text) {
        $processorModelName = version_compare(Mage::getVersion(), '1.3.3.0', '>') ? 'widget/template_filter' : 'core/email_template_filter';
        $processor = Mage::getModel($processorModelName);
        if ($processor instanceof Mage_Core_Model_Email_Template_Filter) {
            return $processor->filter($text);
        }
        return $text;
    }

}
