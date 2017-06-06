<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_CustomerCredit_Block_Rules_Editable extends Mage_Core_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
	    $valueName = $element->getValueName();

	    if ($valueName==='') {
	        $valueName = '...';
	    } else {
	        $valueName = Mage::helper('core/string')->truncate($valueName, 30);
	    }
	    if ($element->getShowAsText()) {
	        $html = ' <input type="hidden" class="hidden" id="'.$element->getHtmlId().'" name="'.$element->getName().'" value="'.$element->getValue().'"/> ';

	        $html.= htmlspecialchars($valueName).'&nbsp;';
	    } else {
    		$html = ' <span class="rule-param"' . ($element->getParamId() ? ' id="' . $element->getParamId() . '"' : '') . '>';

    		$html.= '<a href="javascript:void(0)" class="label">';

    		$html.= htmlspecialchars($valueName);

    		$html.= '</a><span class="element"> ';

    		$html.= $element->getElementHtml();

    		if ($element->getExplicitApply()) {
    		    $html.= ' <a href="javascript:void(0)" class="rule-param-apply"><img src="'.$this->getSkinUrl('images/rule_component_apply.gif').'" class="v-middle" alt="'.$this->__('Apply').'" title="'.$this->__('Apply').'" /></a> ';
    		}

    		$html.= '</span></span>&nbsp;';
	    }
		return $html;
	}
}