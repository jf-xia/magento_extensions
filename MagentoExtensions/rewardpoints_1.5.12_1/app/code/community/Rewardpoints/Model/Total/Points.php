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
 * @copyright  Copyright (c) 2012 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Rewardpoints_Model_Total_Points extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    /*public function __construct()
    {
        //parent::__construct();
        $this->setCode('rewardpoints');
    }*/
    
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        
        if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
            $items = $this->_getAddressItems($address);
        } else {
            $items = $address->getAllItems();
        }
        
        if (!count($items)) {
            return $this;
        }

        $totalPPrice = 0;
        $totalPBasePrice = 0;
        
        $this->checkAutoUse();
        $creditPoints = $this->getCreditPoints();        
        
        $subtotalWithDiscount = 0;
        $baseSubtotalWithDiscount = 0;
        
        $totalDiscountAmount = 0;
        $baseTotalDiscountAmount = 0;
        
        if ($creditPoints > 0){
            $pointsAmount = Mage::helper('rewardpoints/data')->convertPointsToMoney($creditPoints);
            foreach ($items as $item) {
                if ($item->getProduct()->isVirtual()) {
                    continue;
                }

                if (Mage::getStoreConfig('rewardpoints/default/process_tax', Mage::app()->getStore()->getId()) == 1 && Mage::getStoreConfig('tax/calculation/apply_after_discount', Mage::app()->getStore()->getId()) == 0){
                    $tax = ($item->getTaxBeforeDiscount() ? $item->getTaxBeforeDiscount() : $item->getTaxAmount());
                    $row_base_total = $item->getBaseRowTotal() + $tax;
                } else {
                    $row_base_total = $item->getBaseRowTotal();
                }            
                $baseDiscountAmount = min($row_base_total - $item->getBaseDiscountAmount(), $pointsAmount);
                
                if ($baseDiscountAmount > 0){
                    $points = -$baseDiscountAmount;
                    $totalPBasePrice += $points;
                    $discountAmount = $address->getQuote()->getStore()->convertPrice($points, false);
                    $totalPPrice += $discountAmount;
                    
                    if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
                        $item->setDiscountAmount(abs($discountAmount)+$item->getDiscountAmount());
                        $item->setBaseDiscountAmount(abs($baseDiscountAmount)+$item->getBaseDiscountAmount());
                    } else {
                        $item->setDiscountAmount(abs($discountAmount)+$item->getDiscountAmount());
                        $item->setBaseDiscountAmount(abs($baseDiscountAmount)+$item->getBaseDiscountAmount());
                        
                        
                        $item->setRowTotalWithDiscount($item->getRowTotal()-$item->getDiscountAmount());
                        $item->setBaseRowTotalWithDiscount($item->getBaseRowTotal()-$item->getBaseDiscountAmount());

                        $subtotalWithDiscount += $item->getRowTotalWithDiscount();
                        $baseSubtotalWithDiscount += $item->getBaseRowTotalWithDiscount();
                    }
                    
                    //$totalDiscountAmount += $item->getDiscountAmount();
                    //$baseTotalDiscountAmount += $item->getBaseDiscountAmount();
                    
                    $totalDiscountAmount += abs($discountAmount);
                    $baseTotalDiscountAmount += abs($baseDiscountAmount);
                    
                    
                }
                
                $pointsAmount -= $baseDiscountAmount;
            }

            //J2T process shipping address
            $shipping_process = Mage::getStoreConfig('rewardpoints/default/process_shipping', Mage::app()->getStore()->getId());
            if (version_compare(Mage::getVersion(), '1.4.0', '>=') && $shipping_process){
                $baseShippingDiscountAmount = min($address->getBaseShippingAmount(), $pointsAmount);
                $points = -$baseShippingDiscountAmount;
                $totalPBasePrice += $points;
                $totalPPrice += $address->getQuote()->getStore()->convertPrice($points, false);
                $pointsAmount -= $baseShippingDiscountAmount;
            }
            //J2T end process shipping address


            if (abs($totalPBasePrice) > 0){
                $points_used = Mage::helper('rewardpoints/data')->convertMoneyToPoints(abs($totalPBasePrice));
                $points_session = Mage::helper('rewardpoints/event')->getCreditPoints();
                if ($points_used < $points_session){
                    Mage::helper('rewardpoints/event')->setCreditPoints($points_used);
                }
            }


            /*$this->_setAmount($totalPPrice)
                ->_setBaseAmount($totalPBasePrice);*/
            
            if ($pts = Mage::helper('rewardpoints/event')->getCreditPoints()){
                $title = Mage::helper('rewardpoints')->__('%s points used', $pts);
                
                $auto_use = Mage::getStoreConfig('rewardpoints/default/auto_use', Mage::app()->getStore()->getId());
                if (!$auto_use){
                    //$title .= ' <a href="javascript:$(\'discountFormPoints2\').submit();" title="'.Mage::helper('rewardpoints')->__('Remove Points').'"><img src="'.Mage::getDesign()->getSkinUrl('images/j2t_delete.gif').'" alt="'.Mage::helper('rewardpoints')->__('Remove Points').'" /></a>';
                    $title .= '<span id="link_j2t_rewards"></span>';
                }
                
                if ($address->getDiscountDescription() != ''){
                    $desc_array = $address->getDiscountDescriptionArray();
                    $desc_array[] = $title;
                    $address->setDiscountDescriptionArray($desc_array);
                    //$address->setDiscountDescriptionArray($couponCode);
                    $address->setDiscountDescription($address->getDiscountDescription().', '.$title);
                } else {
                    $address->setDiscountDescription($title);
                    $address->setDiscountDescriptionArray(array($title));
                }
                
                if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
                    
                    $address->setDiscountAmount($address->getDiscountAmount()+$totalPPrice);                
                    $address->setBaseDiscountAmount($address->getBaseDiscountAmount()+$totalPBasePrice);
                    
                    $this->_addAmount($totalPPrice);
                    $this->_addBaseAmount($totalPBasePrice);
                } else {
                    
                    
                    $address->setDiscountAmount($address->getDiscountAmount()+$totalDiscountAmount);
                    $address->setSubtotalWithDiscount($address->getSubtotalWithDiscount()+$subtotalWithDiscount);
                    $address->setBaseDiscountAmount($baseTotalDiscountAmount);
                    $address->setBaseSubtotalWithDiscount($baseSubtotalWithDiscount);
                    
                    if ($coupon = $address->getCouponCode()){
                        $address->setCouponCode($address->getCouponCode().', '.$title);
                    } else {
                        $address->setCouponCode($title);
                    }
                    
                    $address->setGrandTotal($address->getGrandTotal() - $totalDiscountAmount);
                    $address->setBaseGrandTotal($address->getBaseGrandTotal()-$subtotalWithDiscount);
                }
            }
            
        }
        
        return $this;
    }

    
    /*public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $pts = $this->getCreditPoints();
        $amount = $address->getRewardpointsAmount();
        
        if ($amount != 0 && $address->getAddressType() == 'shipping') {
            $title = Mage::helper('rewardpoints')->__('%s points used', $pts);
            //skin/frontend/default/default/images/j2t_delete.gif
            $auto_use = Mage::getStoreConfig('rewardpoints/default/auto_use', Mage::app()->getStore()->getId());
            if (!$auto_use){
                $title .= ' <a href="javascript:$(\'discountFormPoints2\').submit();" title="'.Mage::helper('rewardpoints')->__('Remove Points').'"><img src="'.Mage::getDesign()->getSkinUrl('images/j2t_delete.gif').'" alt="'.Mage::helper('rewardpoints')->__('Remove Points').'" /></a>';
            }
            
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => $title,
                'value' => $amount
            ));
        }
        return $this;
    }*/

    
    /*public function getLabel()
    {
        return Mage::helper('rewardpoints')->__('Points');
    }*/
    
    protected function getCreditPoints()
    {
        return Mage::helper('rewardpoints/event')->getCreditPoints();
    }
    
    protected function checkAutoUse(){
        $customer = Mage::getSingleton('customer/session');
        if ($customer->isLoggedIn()){
            $customerId = Mage::getModel('customer/session')->getCustomerId();
            $auto_use = Mage::getStoreConfig('rewardpoints/default/auto_use', Mage::app()->getStore()->getId());
            if ($auto_use){
                if (Mage::getStoreConfig('rewardpoints/default/flatstats', Mage::app()->getStore()->getId())){
                    $reward_model = Mage::getModel('rewardpoints/flatstats');
                    $customer_points = $reward_model->collectPointsCurrent($customerId, Mage::app()->getStore()->getId());
                } else {
                    $reward_model = Mage::getModel('rewardpoints/stats');
                    $customer_points = $reward_model->getPointsCurrent($customerId, Mage::app()->getStore()->getId());
                }

                if ($customer_points && $customer_points > Mage::helper('rewardpoints/event')->getCreditPoints()){
                    $cart_amount = Mage::getModel('rewardpoints/discount')->getCartAmount();
                    $cart_amount = Mage::helper('rewardpoints/data')->processMathValue($cart_amount);
                    $points_value = min(Mage::helper('rewardpoints/data')->convertMoneyToPoints($cart_amount), (int)$customer_points);

                    Mage::getSingleton('customer/session')->setProductChecked(0);
                    Mage::helper('rewardpoints/event')->setCreditPoints($points_value);
                }
            }
        }
    }
}
