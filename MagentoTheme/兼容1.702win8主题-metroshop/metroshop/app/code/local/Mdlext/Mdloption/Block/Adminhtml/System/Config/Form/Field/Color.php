<?php
class Mdlext_Mdloption_Block_Adminhtml_System_Config_Form_Field_Color extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = parent::_getElementHtml($element);

        if ( !Mage::registry('mColorPicker') ) {
            $html .= '
                <script type="text/javascript" src="'.$this->getJsUrl('bootstrap/jquery-1.8.2.min.js').'"></script>
                <script type="text/javascript" src="'.$this->getJsUrl('bootstrap/mColorPicker.min.js').'"></script>
                <script type="text/javascript">
                jQuery.noConflict();
                jQuery.fn.mColorPicker.init.replace = false;
                jQuery.fn.mColorPicker.init.enhancedSwatches = false;
                jQuery.fn.mColorPicker.init.allowTransparency = true;
                jQuery.fn.mColorPicker.init.showLogo = false;
                jQuery.fn.mColorPicker.defaults.imageFolder = "'.$this->getJsUrl('bootstrap/mColorPicker/').'";
                </script>
                ';
            Mage::register('mColorPicker', 1);
        }
		$html .= '
        <script type="text/javascript">
        jQuery(function($){
            $("#'.$element->getHtmlId().'").width("200px").attr("data-hex", true).mColorPicker({swatches: [
              "#9a1212",
              "#93ad2a",
              "#00ff00",
              "#00ffff",
              "#0000ff",
              "#ff00ff",
              "#ff0000",
              "#4c2b11",
              "#000000",
              "#ffffff"
            ]});
        });
        </script>
        ';
        return $html;
    }
}