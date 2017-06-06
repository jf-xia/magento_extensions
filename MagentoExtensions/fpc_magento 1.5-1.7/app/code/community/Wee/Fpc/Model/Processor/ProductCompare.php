<?php

class Wee_Fpc_Model_Processor_ProductCompare extends Wee_Fpc_Model_Processor_Abstract
{
    const PRODUCT_COMPARE_KEY = 'product_compare';

    public function prepareContent($content, array $requestParameter)
    {
        $block = new Mage_Catalog_Block_Product_Compare_Sidebar();
        $block->setTemplate('wee_fpc/catalog/product/compare/sidebar.phtml');
        $block->setLayout(Mage::app()->getLayout());
        $block->setLoadFromFpc(true);
        $blockContent = $block->toHtml();
        return $this->_replaceContent(self::PRODUCT_COMPARE_KEY, $blockContent, $content);
    }
}