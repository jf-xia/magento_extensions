<?php

class Topbuy_Ajax_Block_Tempblock extends Mage_Core_Block_Template {

//    protected function _construct() {
//        $this->addData(array(
//            'cache_lifetime' => "3600",
//            'cache_tags' => array("tempblock"),
//        ));
//    }
   
    public function getJustBought() {
        $proArray = Mage::helper('homepage')->getJustBought(0);
        krsort($proArray);
        $customerBuyHtml = '';
        foreach ($proArray as $pro){
            $customerBuyHtml .= $pro;
        }
        return $customerBuyHtml;
    }
   
    public function getBasket() {
        $id_qty=Mage::getSingleton('checkout/session')->getBasket();
        $id=strstr($id_qty, '_', true);
        $qty=substr(strstr($id_qty, '_'),1,strlen(strstr($id_qty, '_')));
        if ($qty==0&&$id) $qty=1;
        $pro = Mage::getModel('catalog/product')->load($id);
        $basketItem = array('name'=>$pro->getName(),'price'=>number_format($pro->getPrice(),2),
            'img'=>$pro->getImageUrl(),'qty'=>$qty);
        return $basketItem;
    }
    
    public function getOrderHistory() {
        $customerid = Mage::getSingleton("customer/session")->getId();
        $orderHistoryModel = Mage::getModel('homepage/ordershistory')->getCollection()
                ->addFilter('id_magcustomer', $customerid);
        return $orderHistoryModel;
    }
    
    public function getCategoryDesc1($categoryIds) {
        $cataDescription = '';
        if(!empty($categoryIds)){
            $cataDesc = Mage::getModel('ajax/prddescription')->getCategoryDesc($categoryIds)->getDescription1();
            if ($cataDesc){
                $cataDescription = '<h3>Important Note:</h3>'.$cataDesc.'<br /><br /><hr /><br />';
            }
        }
        return $cataDescription;
    }
    
    public function getCategoryDesc2($categoryIds) {
        $cataDescription = '';
        if(!empty($categoryIds)){
            $cataDesc = Mage::getModel('ajax/prddescription')->getCategoryDesc($categoryIds)->getDescription2();
            if ($cataDesc){
                $cataDescription = '<h3>Important Note:</h3>'.$cataDesc.'<br /><br /><hr /><br />';
            }
        }
        return $cataDescription;
    }
  
    //customer/account/navigation.phtml
    public function getReseller() {
        $isReseller = Mage::getModel('homepage/reseller')->getCollection()->addFilter('customerid',Mage::getSingleton("customer/session")->getId())->getFirstItem()->getId();
        $aHtml='';
        if ($isReseller){
            $aHtml = '<a href="'.$this->getUrl().'homepage/business/resellerinfo/" rel="nofollow" >Reseller Info</a>';
        }
        return $aHtml;
    }

    //catalog/product/view.phtml
    public function getStockQty($_product) {
        return Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
    }
    //catalog/layer/view.phtml
    public function getFilter() {
        $categoryfilterModel = Mage::getModel('homepage/categoryfilter')->getCollection()
                ->addFilter('cf_idcategory', Mage::registry('current_category')->getId());
        $filterArray = array();
        foreach ($categoryfilterModel as $_filter) {
            array_push($filterArray, $_filter->getCfFiltername());
        }
        array_push($filterArray, 'price');
        return $filterArray;
    }

    //sales/order/items/renderer/default.phtml
    public function getProduct($_item) {
        return Mage::getModel('catalog/product')->load($_item->getProductId());
    }

    public function getTrackStatus($_item) {
        $imgUrl = 'images/layout/track_status'.Mage::helper('ajax')->getTrackStatus($_item).'.png';
        return $imgUrl;
    }

    //category.phtml
    public function getCategoryFilter() {
        $currentCat = Mage::registry('current_category');
        $cfHtml ='';
//        if ($currentCat->getLevel() != 2) {
            $cfHtml = '<dt><span>DEPARTMENT</span></dt><dd>';
            if ($currentCat->getLevel() == 5) {
                $cfHtml .= "<h4 class='lv1'><a href='" . $currentCat->getParentCategory()->getParentCategory()->getParentCategory()->getUrl() . "'>" . $currentCat->getParentCategory()->getParentCategory()->getParentCategory()->getName() . "</a></h4>";
                $cfHtml .= "<h4 class='lv2'><a href='" . $currentCat->getParentCategory()->getParentCategory()->getUrl() . "'>" . $currentCat->getParentCategory()->getParentCategory()->getName() . "</a></h4>";
                $cfHtml .= "<h4 class='lv3'><a href='" . $currentCat->getParentCategory()->getUrl() . "'>" . $currentCat->getParentCategory()->getName() . "</a></h4>";
//                $cfHtml .= "<h4 class='lv4'>" . $currentCat->getName() . "</h4>";
                if (!$currentCat->hasChildren()) {
                    $cfHtml .= "<ul class='topbuy-subcategory lv3'>";
                    $subCategories = $currentCat->getParentCategory()->getChildrenCategories();
                    foreach ($subCategories as $subfilter) {
                        if ($subfilter->getName()==$currentCat->getName()) {
                            $cfHtml .= "<li><a href='" . $subfilter->getUrl() . "' ><b>" . $subfilter->getName() . "</b></a></li>"; //<span>(".$subfilter->getProductCount().")</span>
                        } else {
                            $cfHtml .= "<li><a href='" . $subfilter->getUrl() . "' >" . $subfilter->getName() . "</a></li>"; //<span>(".$subfilter->getProductCount().")</span>
                        }
                    }
                }
            }
            if ($currentCat->getLevel() == 4) {
                $cfHtml .= "<h4 class='lv1'><a href='".$currentCat->getParentCategory()->getParentCategory()->getUrl()."'>".$currentCat->getParentCategory()->getParentCategory()->getName()."</a></h4>";
                $cfHtml .= "<h4 class='lv2'><a href='" . $currentCat->getParentCategory()->getUrl() . "'>" . $currentCat->getParentCategory()->getName() . "</a></h4>";
                if ($currentCat->hasChildren()) {
                    $cfHtml .= "<h4 class='lv3'>" . $currentCat->getName() . "</h4>";
                    $cfHtml .= "<ul class='topbuy-subcategory lv3'>";
                    $subCategories = $currentCat->getChildrenCategories();
                    foreach ($subCategories as $subfilter) {
                        $cfHtml .= "<li><a href='" . $subfilter->getUrl() . "' >" . $subfilter->getName() . "</a></li>"; //<span>(".$subfilter->getProductCount().")</span>
                    }
                } else {
                    $cfHtml .= "<ul class='topbuy-subcategory lv2'>";
                    $subCategories = $currentCat->getParentCategory()->getChildrenCategories();
                    foreach ($subCategories as $subfilter) {
                        if ($subfilter->getName()==$currentCat->getName()) {
                            $cfHtml .= "<li><a href='" . $subfilter->getUrl() . "' ><b>" . $subfilter->getName() . "</b></a></li>"; //<span>(".$subfilter->getProductCount().")</span>
                        } else {
                            $cfHtml .= "<li><a href='" . $subfilter->getUrl() . "' >" . $subfilter->getName() . "</a></li>"; //<span>(".$subfilter->getProductCount().")</span>
                        }
                    }
                }
            }
            if ($currentCat->getLevel() == 3) {
                $cfHtml .= "<h4 class='lv1'><a href='".$currentCat->getParentCategory()->getUrl()."'>".$currentCat->getParentCategory()->getName()."</a></h4>";
//                $cfHtml .= "<h4 class='lv" . ($currentCat->getLevel() - 2) . "'>" . $currentCat->getName() . "</h4>";
//                $cfHtml .= "<ul class='topbuy-subcategory lv" . ($currentCat->getLevel() - 2) . "'>";
//                if ($currentCat->hasChildren()) {
//                    $ids = $currentCat->getChildren();
//                    $subCategories = Mage::getModel('catalog/category')->getCollection();
//                    $subCategories->getSelect()->where("e.entity_id in ($ids)");
//                    $subCategories->addAttributeToSelect('*');
//                    $subCategories->load();
//                    foreach ($subCategories as $subfilter) {
//                        $cfHtml .= "<li><a href='" . $subfilter->getUrl() . "' >" . $subfilter->getName() . "</a></li>"; //<span>(".$subfilter->getProductCount().")</span>
//                    }
//                }
            }
            if($currentCat->getLevel()==2||$currentCat->getLevel()==3) { 
                $cfHtml .= "<h4 class='lv".($currentCat->getLevel()-1)."'>".$currentCat->getName()."</h4>";
                $cfHtml .= "<ul class='topbuy-subcategory lv".($currentCat->getLevel()-1)."'>";
                if($currentCat->hasChildren()) {
                    $subCategories = $currentCat->getChildrenCategories();
                    foreach ($subCategories as $subfilter){ 
                        $cfHtml .= "<li><a href='".$subfilter->getUrl()."' >".$subfilter->getName()."</a></li>";//<span>(".$subfilter->getProductCount().")</span>
                    } 
                }
            } 
            $cfHtml .= '</ul></dd>';
//        }
        return $cfHtml;
    }

}