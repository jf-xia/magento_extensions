<?php


class Wee_Fpc_Block_Checkout_Links extends Mage_Checkout_Block_Links
{
    const CART_LINK_BEFORE_TEXT = '<!--cart_link_start-->';
    const CART_LINK_AFTER_TEXT = '<!--cart_link_end-->';

    public function addCartLink()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Mage_Checkout')) {
            $count = $this->helper('checkout/cart')->getSummaryCount();
            if( $count == 1 ) {
                $text = $this->__('My Cart (%s item)', $count);
            } elseif( $count > 0 ) {
                $text = $this->__('My Cart (%s items)', $count);
            } else {
                $text = $this->__('My Cart');
            }
            $parentBlock->addLink($text, 'checkout/cart', $text, true, array(), 50, null, 'class="top-link-cart"', self::CART_LINK_BEFORE_TEXT, self::CART_LINK_AFTER_TEXT);
        }
        return $this;
    }
}