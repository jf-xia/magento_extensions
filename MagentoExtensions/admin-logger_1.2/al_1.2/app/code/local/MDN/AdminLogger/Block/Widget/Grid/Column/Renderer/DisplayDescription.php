<?php

class MDN_AdminLogger_Block_Widget_Grid_Column_Renderer_DisplayDescription extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {

        $html = $row->getal_description();
        return $html;
    }

}

