<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Pgrid
*/
class Amasty_Pgrid_Block_Adminhtml_Catalog_Product_Grid_Renderer_Multiselect extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $options = $this->getColumn()->getOptions();
        $showMissingOptionValues = (bool)$this->getColumn()->getShowMissingOptionValues();
        if (!empty($options) && is_array($options)) {
            $value = $row->getData($this->getColumn()->getIndex());
            $values = explode(',', $value);
            if (is_array($values))
            {
                foreach ($values as &$item)
                {
                    if (isset($options[$item]))
                    $item = $options[$item];
                }
                $value = implode(', ', $values);
                return $value;
            }
            return '';
        }
    }
}