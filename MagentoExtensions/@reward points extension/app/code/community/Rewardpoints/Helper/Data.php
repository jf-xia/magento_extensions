<?php
/**
 * J2T RewardsPoint2
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
 * @copyright  Copyright (c) 2011 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Rewardpoints_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getReferalUrl()
    {
        return $this->_getUrl('rewardpoints/');
    }
    
    
    public function getResizedUrl($imgName,$x,$y=NULL){
        $imgPathFull=Mage::getBaseDir("media").DS.$imgName;
 
        $widht=$x;
        $y?$height=$y:$height=$x;
        $resizeFolder="j2t_resized";
        $imageResizedPath=Mage::getBaseDir("media").DS.$resizeFolder.DS.$imgName;
        
        if (!file_exists($imageResizedPath) && file_exists($imgPathFull)){
            $imageObj = new Varien_Image($imgPathFull);
            $imageObj->constrainOnly(true);
            $imageObj->keepAspectRatio(true);
            $imageObj->keepTransparency(true);
            $imageObj->resize($widht,$height);
            $imageObj->save($imageResizedPath);
        }
        
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$resizeFolder.DS.$imgName;
    }
    
    
    
    public function getProductPointsText($product, $noCeil = false, $from_list = false){
        $points = $this->getProductPoints($product, $noCeil, $from_list);        
        if ($points){
            
            $img = '';
            
            if (Mage::getStoreConfig('rewardpoints/design/small_inline_image_show', Mage::app()->getStore()->getId())){
                //$img = '<img src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).DS. 'j2t_image_small.png' .'" alt="" width="16" height="16" /> ';
                $img = '<img src="'.$this->getResizedUrl('j2t_image_small.png', 16, 16) .'" alt="" width="16" height="16" /> ';
            }
            
            $return = '<p class="j2t-loyalty-points inline-points">'.$img. Mage::helper('rewardpoints')->__("With this product, you earn <span id='j2t-pts'>%d</span> loyalty point(s).", $points) . '</p>';
            return $return;
        }
        return '';
    }
    
    
    public function processMathBaseValue($amount, $specific_rate = null){
        $math_method = Mage::getStoreConfig('rewardpoints/default/math_method', Mage::app()->getStore()->getId());
        if ($math_method == 1){
            $amount = round($amount);
        } elseif ($math_method == 0) {
            $amount = floor($amount);
        }
        return $amount;
    }
    

    public function processMathValue($amount, $specific_rate = null){
        $math_method = Mage::getStoreConfig('rewardpoints/default/math_method', Mage::app()->getStore()->getId());
        if ($math_method == 1){
            $amount = round($amount);
        } elseif ($math_method == 0) {
            $amount = floor($amount);
        }
        return $this->ratePointCorrection($amount, $specific_rate);
    }

    public function processMathValueCart($amount, $specific_rate = null){
        $math_method = Mage::getStoreConfig('rewardpoints/default/math_method', Mage::app()->getStore()->getId());
        if ($math_method == 1){
            $amount = round($amount);
        } elseif ($math_method == 0) {
            $amount = floor($amount);
        }
        return $amount;
        //return $this->ratePointCorrection($amount, $specific_rate);
    }

    public function ratePointCorrection($points, $rate = null){
        if ($rate == null){
            $baseCurrency = Mage::app()->getBaseCurrencyCode();
            $currentCurrency = Mage::app()->getStore()->getCurrentCurrencyCode();
            $rate = Mage::getModel('directory/currency')->load($baseCurrency)->getRate($currentCurrency);
        }
        if (Mage::getStoreConfig('rewardpoints/default/process_rate', Mage::app()->getStore()->getId())){
            /*if ($rate > 1){
                return $points * $rate;
            } else {*/
                return $points / $rate;
            //}
        } else {
            return $points;
        }
    }

    public function rateMoneyCorrection($money, $rate = null){
        if ($rate == null){
            $baseCurrency = Mage::app()->getBaseCurrencyCode();
            $currentCurrency = Mage::app()->getStore()->getCurrentCurrencyCode();
            $rate = Mage::getModel('directory/currency')->load($baseCurrency)->getRate($currentCurrency);
        }
        
        if (Mage::getStoreConfig('rewardpoints/default/process_rate', Mage::app()->getStore()->getId())){
            /*if ($rate < 1){
                return $money / $rate;
            } else {
                return $money * $rate;
            }*/
                
            return $money * $rate;
        } else {
            return $money;
        }
        
    }

    public function isCustomProductPoints($product){
        $catalog_points = Mage::getModel('rewardpoints/catalogpointrules')->getAllCatalogRulePointsGathered($product);
        if ($catalog_points === false){
            return true;
        }
        $attribute_restriction = Mage::getStoreConfig('rewardpoints/default/process_restriction', Mage::app()->getStore()->getId());
        $product_points = $product->getData('reward_points');
        if ($product_points > 0){
            return true;
        }
        return false;
    }
    

    public function getProductPoints($product, $noCeil = false, $from_list = false){
        if ($from_list){
            $product = Mage::getModel('catalog/product')->load($product->getId());            
        }
        
        $catalog_points = Mage::getModel('rewardpoints/catalogpointrules')->getAllCatalogRulePointsGathered($product);
        if ($catalog_points === false){
            return 0;
        }

        $attribute_restriction = Mage::getStoreConfig('rewardpoints/default/process_restriction', Mage::app()->getStore()->getId());
        $product_points = $product->getRewardPoints();
        
        if ($product_points > 0){
            $points_tobeused = $product_points + (int)$catalog_points;
            if (Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId())){
                if ((int)Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId()) < $points_tobeused){
                    return Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId());
                }
            }
            return ($points_tobeused);
        } else if (!$attribute_restriction) {
            //get product price include vat
            $_finalPriceInclTax  = Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), true);
            $_weeeTaxAmount = Mage::helper('weee')->getAmount($product);

            $price = Mage::helper('core')->currency($_finalPriceInclTax+$_weeeTaxAmount,false,false);
            $money_to_points = Mage::getStoreConfig('rewardpoints/default/money_points', Mage::app()->getStore()->getId());
            if ($money_to_points > 0){
                $price = $price * $money_to_points;
            }

            $points_tobeused = $this->processMathValue($price + (int)$catalog_points);

            if (Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId())){
                if ((int)Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId()) < $points_tobeused){
                    return Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', Mage::app()->getStore()->getId());
                }
            }

            if ($noCeil)
                return $points_tobeused;
            else {
                return ceil($points_tobeused);
            }

        }
        return 0;
    }

    public function convertMoneyToPoints($money){
        $points_to_get_money = Mage::getStoreConfig('rewardpoints/default/points_money', Mage::app()->getStore()->getId());
        $money_amount = $this->processMathValue($money*$points_to_get_money);
        
        return $this->rateMoneyCorrection($money_amount);
        //return $money_amount;
    }
    
    
    public function convertBaseMoneyToPoints($money){
        $points_to_get_money = Mage::getStoreConfig('rewardpoints/default/points_money', Mage::app()->getStore()->getId());
        $money_amount = $this->processMathBaseValue($money*$points_to_get_money);
        
        return $money_amount;
    }


    public function convertProductMoneyToPoints($money){
        $points_to_get_money = Mage::getStoreConfig('rewardpoints/default/money_points', Mage::app()->getStore()->getId());
        $money_amount = $this->processMathValue($money*$points_to_get_money);
        return $this->rateMoneyCorrection($money_amount);
        //return $money_amount;
    }

    public function convertPointsToMoney($points_to_be_used){
        $customerId = Mage::getModel('customer/session')
                                        ->getCustomerId();
        
        $reward_model = Mage::getModel('rewardpoints/stats');
        $current = $reward_model->getPointsCurrent($customerId, Mage::app()->getStore()->getId());

        if ($current < $points_to_be_used) {
            Mage::getSingleton('checkout/session')->addError(Mage::helper('rewardpoints')->__('Not enough points available.'));
            Mage::helper('rewardpoints/event')->setCreditPoints(0);
            return 0;
        }
        $step = Mage::getStoreConfig('rewardpoints/default/step_value', Mage::app()->getStore()->getId());
        $step_apply = Mage::getStoreConfig('rewardpoints/default/step_apply', Mage::app()->getStore()->getId());
        if ($step > $points_to_be_used && $step_apply){
            Mage::getSingleton('checkout/session')->addError(Mage::helper('rewardpoints')->__('The minimum required points is not reached.'));
            Mage::helper('rewardpoints/event')->setCreditPoints(0);
            return 0;
        }

        
        if ($step_apply){
            if (($points_to_be_used % $step) != 0){
                Mage::getSingleton('checkout/session')->addError(Mage::helper('rewardpoints')->__('Amount of points wrongly used.'));
                Mage::helper('rewardpoints/event')->setCreditPoints(0);
                return 0;
            }
        }

        $points_to_get_money = Mage::getStoreConfig('rewardpoints/default/points_money', Mage::app()->getStore()->getId());
        $discount_amount = $this->processMathValueCart($points_to_be_used/$points_to_get_money);

        //return $this->ratePointCorrection($discount_amount);
        return $discount_amount;
    }

    public function getPointsOnOrder($cartLoaded = null, $cartQuote = null, $specific_rate = null, $exclude_rules = false, $storeId = false){
        $rewardPoints = 0;
        $rewardPointsAtt = 0;

        if (!$storeId){
            $storeId = Mage::app()->getStore()->getId();
        }
        
        //get points cart rule
        if (!$exclude_rules){
            if ($cartLoaded != null){
                $points_rules = Mage::getModel('rewardpoints/pointrules')->getAllRulePointsGathered($cartLoaded);
            } else {
                $points_rules = Mage::getModel('rewardpoints/pointrules')->getAllRulePointsGathered();
            }
            if ($points_rules === false){
                return 0;
            }
            $rewardPoints += (int)$points_rules;
        }
        
        
        if ($cartLoaded == null){
            $cartHelper = Mage::helper('checkout/cart');
            $items = $cartHelper->getCart()->getItems();
        } elseif ($cartQuote != null){
            $items = $cartQuote->getAllItems();
        }else {
            $items = $cartLoaded->getAllItems();
        }

        $attribute_restriction = Mage::getStoreConfig('rewardpoints/default/process_restriction', $storeId);

        //$cart_amount = 0;
        foreach ($items as $_item){
            
            /*$_product = Mage::getModel('catalog/product')->load($_item->getParentItem()->getProductId());
                $parent_points_rule = Mage::getModel('rewardpoints/catalogpointrules')->getAllCatalogRulePointsGathered($_product);
                echo $parent_points_rule;*/
            
            if ($_item->getHasChildren()) {
                continue;
            }
            
            
            if ($_item->getParentItemId()) {
                if ($cartLoaded == null || $cartQuote != null){
                    $item_qty = $_item->getParentItem()->getQty();
                } else {
                    $item_qty = $_item->getParentItem()->getQtyOrdered();
                }
            } else {
                if ($cartLoaded == null || $cartQuote != null){
                    $item_qty = $_item->getQty();
                } else {
                    $item_qty = $_item->getQtyOrdered();
                }
            }
            
            
            $_product = Mage::getModel('catalog/product')->load($_item->getProductId());
            $catalog_points = Mage::getModel('rewardpoints/catalogpointrules')->getAllCatalogRulePointsGathered($_product);
            if ($catalog_points === false){
                continue;
            } else if(!$attribute_restriction) {
                
                //$rewardPoints += (int)$catalog_points * $_item->getQty();
                /*if ($cartLoaded == null || $cartQuote != null){
                    $rewardPoints += (int)($catalog_points * $_item->getQty());
                } else {
                    $rewardPoints += (int)($catalog_points * $_item->getQtyOrdered());
                }*/
                $rewardPoints += (int)($catalog_points * $item_qty);
            }
            $product_points = $_product->getData('reward_points');
            
            if ($product_points > 0){
                if ($_item->getQty() > 0 || $_item->getQtyOrdered() > 0){
                    /*if ($cartLoaded == null || $cartQuote != null){
                        $rewardPointsAtt += (int)($product_points * $_item->getQty());
                    } else {
                        $rewardPointsAtt += (int)($product_points * $_item->getQtyOrdered());
                    }*/
                    $rewardPointsAtt += (int)($product_points * $item_qty);
                }
            } else if(!$attribute_restriction) {
                //check if product is option product (bundle product)

                if (!$_item->getParentItemId()) {
                    //v.2.0.0 exclude_tax
                    if (Mage::getStoreConfig('rewardpoints/default/exclude_tax', $storeId)){
                        $tax_amount = 0;
                    } else {
                        $tax_amount = $_item->getTaxAmount();
                    }

                    $price = $_item->getRowTotal() + $tax_amount - $_item->getDiscountAmount();
                    $rewardPoints += (int)(Mage::getStoreConfig('rewardpoints/default/money_points', $storeId) * $price);
                } else {
                    //echo "ici";
                    //die;
                    //fix for configurable product check points rules
                    $current_item = $_item;            
                    if ($_item->getParentItemId()) {
                        $current_item = $_item->getParentItem();
                    }
                    
                    if (Mage::getStoreConfig('rewardpoints/default/exclude_tax', $storeId)){
                        $tax_amount = 0;
                    } else {
                        $tax_amount = $current_item->getTaxAmount();
                    }

                    $price = $current_item->getRowTotal() + $tax_amount - $current_item->getDiscountAmount();
                    $rewardPoints += (int)(Mage::getStoreConfig('rewardpoints/default/money_points', $storeId) * $price);
                    //echo $price;
                    //die;
                    
                    
                }
                
            }
            
            
            //v.2.0.0 exclude_tax
            /*if (Mage::getStoreConfig('rewardpoints/default/exclude_tax', $storeId)){
                $tax_amount = 0;
            } else {
                $tax_amount = $_item->getTaxAmount();
            }
            $cart_amount += $_item->getRowTotal() + $tax_amount - $_item->getDiscountAmount();*/
            
        }
        $rewardPoints = $this->processMathValue($rewardPoints, $specific_rate) + $rewardPointsAtt;

        if (Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', $storeId)){
            if ((int)Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', $storeId) < $rewardPoints){
                return ceil(Mage::getStoreConfig('rewardpoints/default/max_point_collect_order', $storeId));
            }
        }

        return ceil($rewardPoints);
    }
}
