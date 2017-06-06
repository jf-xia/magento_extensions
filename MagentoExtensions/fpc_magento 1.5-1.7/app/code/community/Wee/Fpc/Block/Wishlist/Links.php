<?php

class Wee_Fpc_Block_Wishlist_Links extends Mage_Wishlist_Block_Links
{
    protected function _toHtml()
    {
        $html = parent::_toHtml();
        if ($html) {
            $html = '<!--wishlist_link_start-->'.$html.'<!--wishlist_link_end-->';
        }
        return $html;
    }
}