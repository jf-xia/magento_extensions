<?php

class Wee_Fpc_Model_Processor_CartLink extends Wee_Fpc_Model_Processor_Abstract
{
    const CART_LINK_KEY = 'cart_link';
    
    protected $_checkoutUrl;
    
    public function prepareContent($content, array $requestParameter)
    {
        Mage::getSingleton('core/session', array('name' => 'frontend'))->start();
        return $this->_replaceContent(self::CART_LINK_KEY, self::_getCartlinkText(), $content);
    }
    
    protected function _getCartlinkText()
    {
        $cartQty = (int)Mage::getSingleton('checkout/cart')->getSummaryQty();
        $helper = Mage::helper('core');
        if ($cartQty == 1) {
            $text = $helper->__('My Cart (%s item)', $cartQty);
        } elseif ($cartQty > 0) {
            $text = $helper->__('My Cart (%s items)', $cartQty);
        } else {
            $text = $helper->__('My Cart');
        }
        $link = sprintf('<a href="%s" title="%s" class="top-link-cart">%s</a>', $this->getCheckoutUrl(), $text, $text);
        return $link;
    }
    
    public function getCheckoutUrl()
    {
        if (null === $this->_checkoutUrl) {
            $this->_checkoutUrl = Mage::getModel('core/url')->getUrl('checkout/cart');
        }
        return $this->_checkoutUrl;
    }
}