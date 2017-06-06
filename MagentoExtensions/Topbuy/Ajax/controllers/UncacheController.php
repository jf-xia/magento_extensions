<?php
class Topbuy_Ajax_UncacheController extends Mage_Core_Controller_Front_Action{
    
    public function MinicartAction() {   
        $layout = Mage::getSingleton('core/layout');
        $block = $layout->createBlock('checkout/cart_sidebar');
        $block->setTemplate('checkout/cart/header.phtml');
        echo $block->toHtml();
	//TODO:Check Error
	die();
    }
    
    public function JustbuyAction() {
        $layout = Mage::getSingleton('core/layout');
        $block = $layout->createBlock('ajax/tempblock');
        echo $block->getJustBought();
	//TODO: check error
	die();
	
    }
    
    public function LastviewAction() {   
        $layout = Mage::getSingleton('core/layout');
        $block = $layout->createBlock('reports/product_viewed');
        $block->setTemplate('catalog/product/view/lastview.phtml');
        echo $block->toHtml();
	//TODO: check errors;
	die();
    }
    
    public function PopupAction() {   
        $popupHtml='';
        if(Mage::getModel('homepage/customerrecord')->isNewCustomer()){
            $layout = Mage::getSingleton('core/layout');
            $block = $layout->createBlock('core/template');
            $block->setTemplate('cmspage/popup.phtml');
            $popupHtml = $block->toHtml();
        }
        echo $popupHtml;
	//TODO: check eroor;
	die();
    }
    
    public function customerrecordAction() {   
//        $pid  = $this->getRequest()->getParam('pid');
//        Mage::getModel('homepage/customerrecord')->cProVisitHRecord($pid); 
////        Mage::getSingleton('review/session')->setRedirectUrl($this->helper('core/url')->getCurrentUrl());
//	die();
    }
    
    public function lsrssAction() {
        $xml_array=simplexml_load_file('http://www.livingstyles.com.au/feed/campaignRSS.xml');
        $i=0;
        $loadFromSSL = $_SERVER['SERVER_PORT']==443?true:false;
        foreach($xml_array as $tmp){  
            echo '  <div id="sidebanner-ls-deal-wrapper">';
            foreach($tmp->item as $item){
                if ($i<3){
                    if ($loadFromSSL){
                        $sslUrl = str_replace("http:","https:",$item->image);
                    } else {
                        $sslUrl = $item->image;
                    }
                    echo '  <div class="sidebanner-ls-deal">
                                <a href="'.$item->link.'" target="_blank" rel="nofollow"><img src="'.$sslUrl.'" width="108"/>
                                <a href="'.$item->link.'" target="_blank" rel="nofollow"><h2>'.$item->title.'</h2></a>
                                <a href="'.$item->link.'" target="_blank" rel="nofollow" class="btn">Go to Sale &rsaquo;</a>
                                </a>
                            </div>';
                    $i++;
                }
            }
            echo '  </div>
                    <a href="http://www.livingstyles.com.au" target="_blank" rel="nofollow" id="sidebanner-ls-deal_linktop"></a>
                    <a href=" http://www.livingstyles.com.au" target="_blank" rel="nofollow" id="sidebanner-ls-deal_linkbottom"></a>';
        }
	die();
    }
    
    public function gbrssAction() {
        $loadFromSSL = $_SERVER['SERVER_PORT']==443?true:false;
        $xml_array=simplexml_load_file('http://www2.topbuy.com.au/tbcart/tbadmin/datafeed/groupbuyRSS.xml');
        $i=0;
        echo '<div id="sidebanner-gb-deal-wrapper">';
        foreach($xml_array as $tmp){  
            foreach($tmp->item as $item){
                if ($i<3){
                    if ($loadFromSSL){
                        $sslUrl = str_replace("http:","https:",$item->image);
                    } else {
                        $sslUrl = $item->image;
                    }
                    $discount = ceil(($item->listprice-$item->price)/$item->listprice*100);
                    echo '<div class="sidebanner-gb-deal">
                            <a href="'.$item->link.'" target="_blank" rel="nofollow">
                            <div class="label-wrapper">
                            <img src="'.$sslUrl.'" width="100"/>
                            <div class="label"></div>
                            </div>
                        <h2>'.$item->title.'</h2>
                            <div class="price">
                            <p class="title">Price:</p>
                            <p class="data">'.$item->price.'</p>
                            <div class="clear"></div>
                            </div>
                            <div class="discount">
                            <p class="title">Discount:</p>
                            <p class="data">'.$discount.'%</p>
                            <div class="clear"></div>
                            </div>
<!--                            <div class="btn">View This Deal</div>   -->
                            </a>
                        </div>';
                    $i++;
                }
            }
        }
        echo '</div>';
	die();
    }
    
    public function cprdviewedAction() {
        Mage::getSingleton('customer/session')->setViewedprd(now());
	die();
    }
}

/*
 * 
 */
