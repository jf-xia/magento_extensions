<?php

class Topbuy_Homepage_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getJustBought($products) {
        $_productCollection = Mage::getModel('sales/order_item')->getCollection()
                ->setOrder('created_at', 'desc');//
        if ($products){
            $_productCollection->addAttributeToFilter('product_id',$products)->setPageSize(5);
        } else {
            $_productCollection->setPageSize(5);
        }
        $customerBuyHtml = '';
        $proArray = array();
        foreach($_productCollection as $pro){
            $_order = Mage::getModel('sales/order')->load($pro->getOrderId());
            $_customer = Mage::getModel('sales/order_address')->load($_order->getBillingAddressId());
            $product = Mage::getModel('catalog/product')->load($pro->getProductId());
            $customerBuyHtml = '
                <li>
                    <a href="'.$product->getProductUrl().'">
                        <img src="'.$product->getImageUrl().'" width="40"/>
                        <p>Someone in '.$_customer->getCity().' just bought <span>'.substr($pro->getName(),0,60).'<!--
                    max 90 chars --> ... <b>@ $'.number_format($product->getPrice(), 2).'</b></span></p>
                        <div class="clear"></div>
                    </a>
                </li>';
            $proArray[$pro->getId()]=$customerBuyHtml;
        }
        
        if ($products){
            $_dProductColl = Mage::getModel('homepage/justbought')->getCollection()
                    ->setOrder('orderdate', 'desc')->setPageSize(5);
            foreach($_dProductColl as $pro){
                $product = Mage::getModel('catalog/product')->load($pro->getProductId());
                $customerBuyHtml = '
                    <li>
                        <a href="'.$product->getProductUrl().'">
                            <img src="'.$product->getImageUrl().'" width="40"/>
                            <p>Someone in '.$pro->getCity().' just bought <span>'.substr($product->getName(),0,60).'<!--
                        max 90 chars --> ... <b>@ $'.number_format($product->getPrice(), 2).'</b></span></p>
                            <div class="clear"></div>
                        </a>
                    </li>';
                $proArray[$pro->getOrderdate()]=$customerBuyHtml;
            }
        }
        return $proArray;
    }
}
	 