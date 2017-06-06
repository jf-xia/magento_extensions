<?php

class Topbuy_Addons_Block_Warranty extends Mage_Core_Block_Template {

    
//    public function getStep1() {
//        $session = Mage::getSingleton("customer/session");
//        $step1Html='';
//        $customerId = $session->getCustomerId();
////        print_r($customer);
//        $orderCollection = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('status', 'complete');
//        $orderCollection->getSelect()->where('customer_id =' . $customerId);
//        foreach($orderCollection as $order){
//            foreach ($order->getAllItems() as $item){
////                print_r($item->getQtyInvoiced());//>0
////                $product = Mage::getModel('catalog/product')->load($item->getProductId());
////                print_r($product->getCategoryIds());//177
//                $warrantyMap = Mage::getModel('addons/warrantymap')->getCollection()->addFieldToFilter('idmagproduct', $item->getProductId())->getFirstItem();
////                print_r($warrantyMap->getData());
//                $warrantyReg = Mage::getModel('addons/warrantyregisterrecord')->load($item->getItemId());
//                if ($item->getQtyInvoiced()>0&&$warrantyMap->hasData()&&!$warrantyReg->hasData()) {
////                    echo $order->getId().'---'.$warrantyMap->getIdmagproduct().'---'.$item->getProductId().'<br>'.$item->getName().'<br>';
//                     $step1Html.='<option value="'.$warrantyMap->getPricefrom().'|'.$warrantyMap->getPriceto().'" >'.$item->getName().'</option>';
//                }
//            }
//        }
//        return $step1Html;
//    }

}