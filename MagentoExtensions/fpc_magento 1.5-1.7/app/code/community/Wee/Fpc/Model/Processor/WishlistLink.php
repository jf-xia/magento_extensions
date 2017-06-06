<?php

class Wee_Fpc_Model_Processor_WishlistLink extends Wee_Fpc_Model_Processor_Abstract
{
    const WISHLIST_KEY = 'wishlist_link';

    public function prepareContent($content, array $requestParameter)
    {
        return $this->_replaceContent(self::WISHLIST_KEY, self::_getWishlistLink(), $content);
    }

    static protected function _getWishlistLink()
    {
        $link = '';
        $helper = Mage::helper('wishlist');
        if ($helper->isAllow()) {
            $count = $helper->getItemCount();
            if ($count > 1) {
                $text = $helper->__('My Wishlist (%d items)', $count);
            }
            else if ($count == 1) {
                $text = $helper->__('My Wishlist (%d item)', $count);
            }
            else {
                $text = $helper->__('My Wishlist');
            }
            $link = sprintf('<li><a href="%s" title="%s">%s</a></li>', $helper->getListUrl(), $text, $text);
        }
        return $link;
    }
}