<?php

class Wee_Fpc_Block_Wishlist_Customer_Sidebar extends Mage_Wishlist_Block_Customer_Sidebar
{
    protected function _toHtml()
    {
        $html = parent::_toHtml();
        if ($html) {
            $html = '<!--wishlist_sidebar_start-->'.$html.'<!--wishlist_sidebar_end-->';
        }
        return $html;
    }
}