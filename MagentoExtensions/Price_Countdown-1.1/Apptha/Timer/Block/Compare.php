<?php
class Apptha_Timer_Block_Compare extends Mage_Catalog_Block_Product_Compare_List
{
    protected function _prepareLayout()
    {
        $block = $this->getLayout()->getBlock('catalog.compare.list');
        if ($block) {
            $block->setTemplate('timer/compare.phtml');
        }
    }
}
?>