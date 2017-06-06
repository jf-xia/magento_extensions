<?php
class ECGiken_Price_Block_Adminhtml_Widget_Grid_Column_Renderer_Currency extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency {

    public function render(Varien_Object $row) {
        return Mage::getModel('directory/currency')->formatTxt($row->getData($this->getColumn()->getIndex()));
    }
}
