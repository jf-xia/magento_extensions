<?php

class Wee_Fpc_Model_Processor_CartSidebar extends Wee_Fpc_Model_Processor_Abstract
{
    const CART_SIDEBAR_KEY = 'sidebar';

    public function prepareContent($content, array $requestParameter)
    {
        $block = new Mage_Checkout_Block_Cart_Sidebar();
        $block->setLayout(Mage::app()->getLayout());
        $block->setTemplate('wee_fpc/cart/sidebar.phtml');
        $blockContent = str_replace('$','\$',$block->toHtml());
        return $this->_replaceContent(self::CART_SIDEBAR_KEY, $blockContent, $content);
    }
}