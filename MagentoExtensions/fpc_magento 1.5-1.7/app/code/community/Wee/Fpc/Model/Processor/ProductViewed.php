<?php

class Wee_Fpc_Model_Processor_ProductViewed extends Wee_Fpc_Model_Processor_Abstract
{
    const PRODUCT_VIEWED_KEY = 'product_viewed';
    const PRODUCT_CONTROLLER_NAME = 'product';

    public function prepareContent($content, array $requestParameter)
    {
        $params = $requestParameter['params'];
        $productId = isset($params['id']) ? $params['id'] : '';
        $block = new Wee_Fpc_Block_Reports_Product_Viewed();
        if ($productId && isset($requestParameter['controller']) && $requestParameter['controller'] == self::PRODUCT_CONTROLLER_NAME) {
            Mage::getSingleton('wee_fpc/productViewed')->addProduct($productId);
            $block->setActiveProductId($productId);
        }
        $block->setTemplate('wee_fpc/reports/product_viewed.phtml');
        $block->setLayout(Mage::app()->getLayout());
        $blockContent = $block->toHtml();
        return $this->_replaceContent(self::PRODUCT_VIEWED_KEY, $blockContent, $content);
    }
}