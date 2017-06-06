<?php

class Topbuy_Orderextend_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getCustomAtt($product_id, $attributeName) {
        $product = Mage::getModel('catalog/product')->load($product_id);
        $attributes = $product->getAttributes();
        $attributeValue = null;
        if(array_key_exists($attributeName , $attributes)) {
            $attributesobj = $attributes["{$attributeName}"];
            $attributeValue = $attributesobj->getFrontend()->getValue($product);
        }
        return $attributeValue;
//        $product = Mage::getModel('catalog/product')->load(1510);
//        $eta = $product->getAttributeText('eta');
//        print_r($eta);
    }

}
	 