<?php
class Swatches_CustomOptions_Block_Catalog_Product_View_Options_Type_Select
    extends Mage_Catalog_Block_Product_View_Options_Type_Select
{
    public function getValuesHtml()
    {
        $html = parent::getValuesHtml();
        
        if (!Mage::getStoreConfig('swatches/customoptions/enabled')) {
            return $html;
        }
        
        $_option = $this->getOption();
        $optionTypes = Mage::helper('swatches_customoptions')->getSelectOptionTypes();
        if (in_array($_option->getType(), $optionTypes)) {
            $flag = true;
            foreach ($_option->getValues() as $_value) {
                if (!$_value->getImage()) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                $images = $this->getLayout()->createBlock('core/template')
                    ->setTemplate('swatches/customoptions/product/view/options/images.phtml')
                    ->setOption($_option)
                    ->toHtml();
                $html = '<div class="swatches-wrapper" style="display:none;">' . $html . '</div>';
                $html = $images.$html;
            }
        }
        
        return $html;
    }
}