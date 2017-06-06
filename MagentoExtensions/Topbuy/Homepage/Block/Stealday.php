<?php

class Topbuy_Homepage_Block_Stealday extends Mage_Core_Block_Template
{
    protected function _construct()
    {
//        $this->addData(array(
//            'cache_lifetime'    => "3600",
//            'cache_tags'        => array("getStealday"),
//        ));
    }

    public function getStealday(){
//        $stealday=Mage::getModel('homepage/stealday')->getCollection();
//        $stealday->getSelect()->where('todate>=?', Mage::getModel('core/date')->date())
//                    ->where('fromdate<=?', Mage::getModel('core/date')->date())
//                    ->where('line_order=?', "0")
//                    ->order('fromdate DESC')->limit(1); 
//        $stealofday="";
//        foreach ($stealday as $_item){
//           $stealofday = "<div id='topbuy-sotd'><a href='".$this->getUrl("homepage/onlinedailydeals").
//                   "' target='_blank'><img width='198' src='http://www2.topbuy.com.au/tbcart/pc/Newsletter/".
//                   date('jFY',strtotime(Mage::getModel('core/date')->date()))."/sotd_home.jpg' /></a></div>";//$this->getSkinUrl('images/banner/promotion/').date('Y-m-d',strtotime($_item->getFromdate())).".jpg' alt='".$_item->getProductdescription()//$_item->getIdproduct().
//        }
        $loadFromSSL = $_SERVER['SERVER_PORT']==443?true:false;
        if ($loadFromSSL){
            $sslUrl = "https://www2.topbuy.com.au/tbcart/pc/Newsletter/";
        } else {
            $sslUrl = "http://www2.topbuy.com.au/tbcart/pc/Newsletter/";
        }
        $hour = date('G',strtotime(Mage::getModel('core/date')->date()));
        if ($hour>11){
           $stealofday = "<div id='topbuy-sotd'><a href='".$this->getUrl("homepage/onlinedailydeals").
                   "' target='_blank'><img width='198' src='".$sslUrl.
                   date('jFY',strtotime(Mage::getModel('core/date')->date()))."/sotd_home.jpg' /></a></div>";
        } else {
           $stealofday = "<div id='topbuy-sotd'><a href='".$this->getUrl("homepage/onlinedailydeals").
                   "' target='_blank'><img width='198' src='http://www2.topbuy.com.au/tbcart/pc/Newsletter/".
                   date('jFY',strtotime(Mage::getModel('core/date')->date())-(3600*24))."/sotd_home.jpg' title='Steal Of The Day' alt='Steal Of The Day' /></a></div>";
        }            
        return $stealofday;
    }

    public function getDeals(){

        $dealModel=Mage::getModel('homepage/stealday')->getCollection();//
        $dealModel->getSelect()->where('fromdate<=?', Mage::getModel('core/date')->date())
                ->where('todate>=?', Mage::getModel('core/date')->date())
                ->order(array('fromdate DESC', 'line_order ASC'))->limit(4); 
        $dealArray=array();
        foreach ($dealModel as $_item){
            $_product=$this->getProduct($_item->getIdproduct());
            $qty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
            $images=$_product->getMediaGalleryImages();
            $imagesHtml='';
            foreach($images as $image){
                if(file_exists($image->getPath())){
                        $imagesHtml .='<li><a class="lightbox" href="'.$image->getUrl().'"><img src="'.$image->getUrl().'" width="44"/></a></li>';
                }
            }
            $dealArray[] = array('idproduct'=>$_item->getIdproduct(),
                                                       'promotion_desc'=>$_item->getPromotionDesc(),
                                                       'todate'=>$_item->getTodate(),
                                                       'max_qty'=>$_item->getMaxQty(),
                                                       'name'=>$_product->getName(),
                                                       'product_url'=>$_product->getProductUrl(),
                                                       'price'=>$_product->getPrice(),
                                                       'extra_description'=>$_item->getExtraDescription(),
                                                       'description'=>$_product->getDescription(),
                                                       'qty'=>$qty,
                                                       'rrp'=>$_product->getListprice(),
                                                       'promotionwasprice'=>$_item->getWasPrice(),
                                                       'shippingtype'=>$_product->getShippingtype(),
                                                       'fixshippingfee'=>$_product->getFixshippingfee(),
                                                       'freeshipping'=>$_product->getFreeshipping(),
                                                       'capshippingfee'=>$_product->getCapshippingfee(),
                                                       'imagesHtml'=>$imagesHtml,
                                                       'image_url'=>$_product->getImageUrl());
        }
        $fishDealModel=Mage::getModel('homepage/stealday')->getCollection();//
        $fishDealModel->getSelect()->where('line_order<>?', 0)
                ->where('fromdate<=?', Mage::getModel('core/date')->date())
                ->where('todate>=?', Mage::getModel('core/date')->date())
                ->order('fromdate DESC')->limit(8); 
        $i=1;
        foreach ($fishDealModel as $_item){
            if ($i<9) {
                if($i==1||$i==2) {
                    $i++;
                    continue;
                }else {
                    $_product=$this->getProduct($_item->getIdproduct());
                    $qty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
                    $images=$_product->getMediaGalleryImages();
                    $imagesHtml='';
                    foreach($images as $image){
                        if(file_exists($image->getPath())){
                                $imagesHtml .='<li><a class="lightbox" href="'.$image->getUrl().'"><img src="'.$image->getUrl().'" width="44"/></a></li>';
                        }
                    }
                    $dealArray[$i] = array('idproduct'=>$_item->getIdproduct(),
                                                        'max_qty'=>$_item->getMaxQty(),
                                                        'promotion_desc'=>$_item->getPromotionDesc(),
                                                        'todate'=>$_item->getTodate(),
                                                        'max_qty'=>$_item->getMaxQty(),
                                                        'name'=>$_product->getName(),
                                                        'product_url'=>$_product->getProductUrl(),
                                                        'price'=>$_product->getPrice(),
//                                                        'description'=>$_product->getDescription(),
                                                        'qty'=>$qty,
                                                        'rrp'=>$_product->getListprice(),
//                                                        'promotionwasprice'=>$_item->getWasPrice(),
                                                        'shippingtype'=>$_product->getShippingtype(),
                                                        'fixshippingfee'=>$_product->getFixshippingfee(),
                                                        'freeshipping'=>$_product->getFreeshipping(),
                                                        'capshippingfee'=>$_product->getCapshippingfee(),
                                                        'capshippingfee'=>$_product->getCapshippingfee(),
                                                        'imagesHtml'=>$imagesHtml,
                                                        'image_url'=>$_product->getImageUrl());
                    $i++;
                }
            }
        }
//        array_push($dealArray[0], $image)
        $products = array();
        $products = array($dealArray[0]['idproduct'],$dealArray[1]['idproduct'],$dealArray[2]['idproduct']);
        $proArray = Mage::helper('homepage')->getJustBought($products);
        krsort($proArray);
        $customerBuyHtml = '';
        foreach ($proArray as $pro){
            $customerBuyHtml .= $pro;
        }
        $dealArray[0]['justBuy']=$customerBuyHtml;
        return $dealArray;
    }

    public function getStealBanner() {
        $frontbanner = Mage::getModel('homepage/frontbanner')->getCollection();
        $frontbanner->getSelect()->where('imageurl<>?', NULL)->where('displayfrom>?', date("Y-m-d H:i:s", strtotime(Mage::getModel('core/date')->date()) - 7 * 24 * 3600))
                ->where('positiontype>?', '200')->where('positiontype<?', '210')
                ->order('displayfrom DESC')->limit(3);
        $stealBanner=array();
        $i = 200 + date("w");
        foreach ($frontbanner->getItems() as $_item) {
            if ($_item->getPositiontype()>=$i)
                $stealBanner[] = array(0 => $_item->getDisplaytitle(), 1 => $_item->getImageurl());
        }
        return $stealBanner;
    }
    
    public function getProduct($productid=0) {
        $product = Mage::getModel('catalog/product')->load($productid);
        return $product;             
    }

}
//            
	

//    public function getProduct($product_id=1510, $attributeName='tf_d_colour') {
//        $product = Mage::getModel('catalog/product')->load($product_id);
//        $attributes = $product->getAttributes();
//        $attributeValue = null;
//        if(array_key_exists($attributeName , $attributes)) {
//            $attributesobj = $attributes["{$attributeName}"];
//            $attributeValue = $attributesobj->getFrontend()->getValue($product);
////            $attributeId = $attributesobj->getFrontend()->getOptionId($product);
//        }
////        $product->setTfDColour(24)->save();
////        echo $product->getColor();
//        echo $attributeValue;
//        echo "<br>";
//        echo $attributeName;
//        echo "<br>";
////        return array();
////        echo $attributeId;
////        $product = Mage::getModel('catalog/product')->load(1510);
////        $eta = $product->getAttributeText('eta');
////        print_r($eta);
//    }

//    public function getBigDeal(){
//
//        $bigDeal=Mage::getModel('homepage/stealday')->getCollection();
//        $bigDeal->getSelect()->where('todate>=?', date("Y-m-d h:i:s",time()))
//                    ->where('fromdate<=?', date("Y-m-d h:i:s",time()))
//                    ->where('line_order=?', "0")
//                    ->order('fromdate DESC')->limit(1); 
//        $bigDealHtml="";
//        foreach ($bigDeal as $_item){
//            $bigDealHtml .= "";
//        }
//        return $bigDealHtml;
//    }
//
//    public function getSmailDeal(){
//
//        $smailDeal=Mage::getModel('homepage/stealday')->getCollection();
//        $smailDeal->getSelect()->where('todate>=?', date("Y-m-d h:i:s",time()))
//                    ->where('fromdate<=?', date("Y-m-d h:i:s",time()))
//                    ->where('line_order>?', "0")->where('line_order<?', "3")
//                    ->order('fromdate DESC')->limit(2); 
//        $smailDealHtml="";
//        foreach ($smailDeal as $_item){
//            $smailDealHtml .= "";
//        }
//        return $smailDealHtml;
//    }
//
//    public function getSmailFish(){
//
//        $smailFish=Mage::getModel('homepage/stealday')->getCollection();
//        $smailFish->getSelect()->where('todate>=?', date("Y-m-d h:i:s",time()))
//                    ->where('fromdate<=?', date("Y-m-d h:i:s",time()))
//                    ->where('line_order>?', "2")
//                    ->order('fromdate DESC')->limit(6); 
//        $smailFishHtml="";
//        foreach ($smailFish as $_item){
//            $smailFishHtml .= "";
//        }
//        return $smailFishHtml;
//    }