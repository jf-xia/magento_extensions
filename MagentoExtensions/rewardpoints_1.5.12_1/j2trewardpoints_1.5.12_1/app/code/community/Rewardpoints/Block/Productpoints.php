<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@j2t-design.com so we can send you a copy immediately.
 *
 * @category   Magento extension
 * @package    RewardsPoint2
 * @copyright  Copyright (c) 2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Rewardpoints_Block_Productpoints extends Mage_Catalog_Block_Product_Abstract
{
    public function getConfigurableProducts($product){
        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null,$product);
    }


    public function getOptions($product)
    {
        $product->getTypeInstance(true)->setStoreFilter($product->getStoreId(), $product);
        $optionCollection = $product->getTypeInstance(true)->getOptionsCollection($product);
        $selectionCollection = $product->getTypeInstance(true)->getSelectionsCollection(
            $product->getTypeInstance(true)->getOptionsIds($product),
            $product
        );
        return $optionCollection->appendSelections($selectionCollection, false, false);
    }


    public function formatOptionPrice($price, $product)
    {
        $priceTax = Mage::helper('tax')->getPrice($product, $price);
        $priceIncTax = Mage::helper('tax')->getPrice($product, $price, true);

        return $priceIncTax;
    }

    public function getJsGrouped($product){
        $config = array();
        
        
        $_associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
        $_hasAssociatedProducts = count($_associatedProducts) > 0;
        if ($_hasAssociatedProducts){
            foreach ($_associatedProducts as $_item){
                $priceValue = Mage::helper('rewardpoints/data')->convertProductMoneyToPoints($_item->getFinalPrice());
                $config[$_item->getId()] = $priceValue;
            }
        }
        
        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            return Mage::helper('core')->jsonEncode($config);
        } else {
            return Zend_Json::encode($config);
        }
    }
    
    public function getJsDownloadable($product)
    {
        $config = array();
        //$coreHelper = Mage::helper('core');
        
        $links = $product->getTypeInstance(true)->getLinks($product);

        foreach ($links as $link) {
            //$config[$link->getId()] = $coreHelper->currency($link->getPrice(), false, false);
            $priceValue = Mage::helper('rewardpoints/data')->convertProductMoneyToPoints(($link->getPrice()));
            $config[$link->getId()] = $priceValue;
        }
        
        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            return Mage::helper('core')->jsonEncode($config);
        } else {
            return Zend_Json::encode($config);
        }
    }
    

    public function getJsOptions($product)
    {
        $config = array();

        foreach ($product->getOptions() as $option) {
            $priceValue = 0;
            if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
                $_tmpPriceValues = array();
                foreach ($option->getValues() as $value) {
                    $tmp_price = Mage::helper('rewardpoints/data')->convertProductMoneyToPoints((Mage::helper('core')->currency($value->getPrice(true), false, false)));
                    $_tmpPriceValues[$value->getId()] = $tmp_price;
                }
                $priceValue = $_tmpPriceValues;
            } else {
                $priceValue = Mage::helper('core')->currency($option->getPrice(true), false, false);
                $priceValue = Mage::helper('rewardpoints/data')->convertProductMoneyToPoints(($priceValue));
            }
            $config[$option->getId()] = $priceValue;
        }

        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            return Mage::helper('core')->jsonEncode($config);
        } else {
            return Zend_Json::encode($config);
        }
    }



    public function getJsBundlePoints($product)
    {
        Mage::app()->getLocale()->getJsPriceFormat();
        $store = Mage::app()->getStore();
        $optionsArray = $this->getOptions($product);
        $selected = array();

        $pts_array = array();

        foreach ($optionsArray as $_option) {
            if (!$_option->getSelections()) {
                continue;
            }
            
            $selectionCount = count($_option->getSelections());

            foreach ($_option->getSelections() as $_selection) {
                $_qty = !($_selection->getSelectionQty()*1)?'1':$_selection->getSelectionQty()*1;
                $price_tmp = $product->getPriceModel()->getSelectionPreFinalPrice($product, $_selection, 1);
                $subprice = $this->formatOptionPrice($price_tmp, $product);

                if (!Mage::helper('rewardpoints/data')->isCustomProductPoints($_selection)){
                    $pts = Mage::helper('rewardpoints/data')->convertProductMoneyToPoints(($subprice));
                } else {
                    $pts = Mage::helper('rewardpoints/data')->getProductPoints($_selection);
                }

                $selection = array (
                    'points' => $pts,
                    'subprice' => $subprice,
                    'optionId' => $_option->getId(),
                );
                $responseObject = new Varien_Object();
                $args = array('response_object'=>$responseObject, 'selection'=>$_selection);
                Mage::dispatchEvent('bundle_product_view_config', $args);
                if (is_array($responseObject->getAdditionalOptions())) {
                    foreach ($responseObject->getAdditionalOptions() as $o=>$v) {
                        $selection[$o] = $v;
                    }
                }

                $pts_array[$_selection->getSelectionId()] = $selection;

                if (($_selection->getIsDefault() || ($selectionCount == 1 && $_option->getRequired())) && $_selection->isSalable()) {
                    $selected[$_option->getId()][] = $_selection->getSelectionId();
                }
            }
            
        }

        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            return Mage::helper('core')->jsonEncode($pts_array);
        } else {
            return Zend_Json::encode($pts_array);
        }

    }



    public function getJsPoints($_product) {
        $attributes = array();
        $attribute_credit = array();

        if ($_product->isConfigurable()){
            $allProducts = $_product->getTypeInstance(true)
                            ->getUsedProducts(null, $_product);
            $allowAttributes = $_product->getTypeInstance(true)
                        ->getConfigurableAttributes($_product);
            
            
            foreach ($allProducts as $product) {
                if ($product->isSaleable()) {
                    $attr_values = array();
                    foreach ($allowAttributes as $attribute) {
                        $productAttribute = $attribute->getProductAttribute();
                        $attributeId = $productAttribute->getId();
                        $attributeValue = $product->getData($productAttribute->getAttributeCode());
                        $attr_values[] = $attributeValue;
                    }
                    $return_val[implode("|",$attr_values)] = Mage::helper('rewardpoints/data')->getProductPoints($product, false, false);
                }
            }
            
            if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
                return Mage::helper('core')->jsonEncode($return_val);
            } else {
                return Zend_Json::encode($return_val);
            }
            
            // end of modifications
            
            
            foreach ($allProducts as $product) {
                if ($product->isSaleable()) {
                    foreach ($allowAttributes as $attribute) {
                        $productAttribute = $attribute->getProductAttribute();
                        $attributeId = $productAttribute->getId();
                        $attributeValue = $product->getData($productAttribute->getAttributeCode());
                        
                        if (!isset($options[$productAttribute->getId()])) {
                            $options[$productAttribute->getId()] = array();
                        }

                        if (!isset($options[$productAttribute->getId()][$attributeValue])) {
                            $options[$productAttribute->getId()][$attributeValue] = array();
                        }
                        
                        
                        $attribute_credit[$attributeValue] = Mage::helper('rewardpoints/data')->getProductPoints($product, false, false);
                        
                        $prices = $attribute->getPrices();
                        if (is_array($prices)) {
                            $attr_list = array();
                            foreach ($prices as $value) {
                                if(!isset($options[$attributeId][$value['value_index']])) {
                                    continue;
                                }
                                $price = $value['pricing_value'];
                                $isPercent = $value['is_percent'];
                                if ($isPercent && !empty($price)) {
                                    $price = $_product->getFinalPrice()*$price/100;
                                }
                                
                                if (!isset($attribute_credit[$attributeValue])){
                                    $attribute_credit[$attributeValue] = array();
                                }
                                $attr_list[] = $value['value_index'];
                                
                                //$attribute_credit[$attributeValue][$value['value_index']] = Mage::helper('rewardpoints/data')->getProductPoints($product, false, false);
                                
                                //$attribute_credit[$value['value_index']] = Mage::helper('rewardpoints/data')->convertProductMoneyToPoints(($price + $_product->getFinalPrice()));
                            }
                            //$attribute_credit[$attributeValue][implode('_',$attr_list)] = Mage::helper('rewardpoints/data')->getProductPoints($product, false, false);
                        }
                    }
                }
            }
        }
        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            return Mage::helper('core')->jsonEncode($attribute_credit);
        } else {
            return Zend_Json::encode($attribute_credit);
        }
    }
}
