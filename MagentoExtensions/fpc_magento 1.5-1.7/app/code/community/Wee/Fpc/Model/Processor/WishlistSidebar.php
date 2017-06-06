<?php

class Wee_Fpc_Model_Processor_WishlistSidebar extends Wee_Fpc_Model_Processor_Abstract
{
    const WISHLIST_SIDEBAR_KEY = 'wishlist_sidebar';

    public function prepareContent($content, array $requestParameter)
    {
        $block = new Wee_Fpc_Block_Wishlist_Customer_Sidebar();
        $block->setTemplate('wishlist/sidebar.phtml');
        $block->setLayout(Mage::app()->getLayout());
        $blockContent = str_replace('$','\$',$block->toHtml());
        return $this->_replaceContent(self::WISHLIST_SIDEBAR_KEY, $blockContent, $content);
    }
}