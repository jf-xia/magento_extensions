<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Block type for showing filters in category view pages.
 * @author Mana Team
 * Injected into layout instead of standard catalog/layer_view in layout XML file.
 */
class Mana_Filters_Block_View_Category extends Mage_Catalog_Block_Layer_View {
	
    /**
     * This method is called during page rendering to generate additional child blocks for this block.
     * @return Mana_Filters_Block_View_Category
     * This method is overridden by copying (method body was pasted from parent class and modified as needed). All
     * changes are marked with comments.
     * @see app/code/core/Mage/Catalog/Block/Layer/Mage_Catalog_Block_Layer_View::_prepareLayout()
     */
    protected function _prepareLayout()
    {
        $stateBlock = $this->getLayout()->createBlock($this->_stateBlockName)
            ->setLayer($this->getLayer());

        $categoryBlock = $this->getLayout()->createBlock($this->_categoryBlockName)
            ->setLayer($this->getLayer())
            ->init();

        $this->setChild('layer_state', $stateBlock);
        $this->setChild('category_filter', $categoryBlock);

        $filterableAttributes = $this->_getFilterableAttributes();
        foreach ($filterableAttributes as $attribute) {
            if ($attribute->getAttributeCode() == 'price') {
                $filterBlockName = $this->_priceFilterBlockName;
            }
            elseif ($attribute->getBackendType() == 'decimal') {
                $filterBlockName = $this->_decimalFilterBlockName;
            }
            else {
                $filterBlockName = $this->_attributeFilterBlockName;
            }

            $this->setChild($attribute->getAttributeCode() . '_filter',
                $this->getLayout()->createBlock($filterBlockName)
                    ->setLayer($this->getLayer())
                    ->setAttributeModel($attribute)
                    ->init());
        }

        $this->getLayer()->apply();

        return $this;
    }
    
    /**
     * This method is called during page rendering to determine block types to use inside layered navigation block. 
     * This method is overridden by copying (method body was pasted from parent class and modified as needed). All
     * changes are marked with comments.
     * @see Mage_Catalog_Block_Layer_View::_initBlocks()
     */
    protected function _initBlocks()
    {
        $this->_stateBlockName = 'catalog/layer_state';
        // MANA BEGIN: replace standard block types with ours
        $this->_categoryBlockName = 'mana_filters/filter_category';
        $this->_attributeFilterBlockName = 'mana_filters/filter_attribute';
        $this->_priceFilterBlockName = 'mana_filters/filter_price';
        $this->_decimalFilterBlockName = 'mana_filters/filter_decimal';
        // MANA END
    }
    
}