<?php
/**
 * Current version of the extension shown at backend configuration form.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Blueknow Recommender
 * extension to newer versions in the future. If you wish to customize it for
 * your needs please save your changes before upgrading.
 * 
 * @category	Blueknow
 * @package		Blueknow_Recommender
 * @copyright	Copyright (c) 2010 Blueknow, S.L. (http://www.blueknow.com)
 * @license		GNU General Public License
 * @author		<a href="mailto:santi.ameller@blueknow.com">Santiago Ameller</a>
 * @since		1.0.1
 * 
 */
class Blueknow_Recommender_Block_System_Config_Form_Field_Version extends Mage_Adminhtml_Block_System_Config_Form_Field {
	
	/**
	 * Return a simple HTML code with the current version of the extension. It is shown at backend configuration form.
	 * @see Mage_Adminhtml_Block_System_Config_Form_Field::_getElementHtml()
	 */
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
		$version = (string) Mage::getConfig()->getModuleConfig('Blueknow_Recommender')->version;
    	return '<strong>'.$version.'</strong>';
    }
}