<?php

/**
 * Faett_Piwik_Block_Tag
 *
 * NOTICE OF LICENSE
 * 
 * Faett_Piwik is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Faett_Piwik is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Faett_Piwik.  If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Faett_Piwik to newer
 * versions in the future. If you wish to customize Faett_Piwik for your
 * needs please refer to http://www.faett.net for more information.
 *
 * @category    Faett
 * @package     Faett_Piwik
 * @copyright   Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license     <http://www.gnu.org/licenses/> 
 * 			    GNU General Public License (GPL 3)
 */

/**
 * Piwik Block page.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Giko_Ajaxlogin_Block_Js extends Mage_Core_Block_Text
{
    /**
     * Prepare and return block's html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!Mage::getStoreConfigFlag('ajaxlogin/settings/enable')) {
            return '';
        }
    	if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                return '';
        }
        
		$cssMain = $this->getSkinUrl('css/ajaxlogin.css'); 
		
        $html = <<<HTML
<!-- AjaxLogin -->
<script type="text/javascript">
$$("a[href*=customer/account/login]").each(function(item) {
	item.href='javascript:void(0)';
	Event.observe(item, 'click', showloginbox);
});
</script>
<!-- End Giko AjaxLogin Code -->
<!-- Get free from http://magento.luochunhui.com/ajaxlogin.html -->
HTML;
        $this->addText($html);

        return parent::_toHtml();
    }
}