<?php

class Wyomind_Datafeedmanager_Block_Adminhtml_Options_Renderer_Code extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        return "<span style='font-family:monospace;font-weight:bold;color:#FF1493;'>{attribute_code,<span style='color:green'>[" . $row->getOptionName() . "]</span>}</span>";
    }
}
