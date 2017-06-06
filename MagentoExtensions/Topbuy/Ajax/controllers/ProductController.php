<?php
/**
 * Product controller
 *D:\DevProgram\wnmp\www\t5\app\code\core\Mage\Catalog\controllers\ProductController.php
 * @category   Mage
 * @package    Mage_Catalog
 */
//require_once 'Mage/Catalog/controllers/ProductController.php';
class Topbuy_Ajax_ProductController extends Mage_Core_Controller_Front_Action
{
    
    public function addonspAction() {
        $idcsgroup  = $this->getRequest()->getParam('idcsgroup');
        $addonsHtml=''; 
        $_coreHelper = Mage::helper('core');
        $csgroupproduct = Mage::getModel('addons/csgroupproduct')->getCollection()->addFilter("idcsgroup", $idcsgroup);
//                    $firstproduct = Mage::getModel('catalog/product')->load($csgroupproduct->getFirstItem()->getIdcsproduct());
        foreach ($csgroupproduct as $_itemp) {
            $addonsproduct = Mage::getModel('catalog/product')->load($_itemp->getIdcsproduct());
            $qtyStock = (int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($addonsproduct)->getQty();
            if($addonsproduct->isSaleable()&&$qtyStock>0) {
                $addonsHtml .='   <li>
                                    <a id="'.Mage::getUrl('ajax/product/index/') . 'pid/'.$addonsproduct->getId().'" href="javascript:void(0);">
                                    <div class="item-img"><img src="'.Mage::helper('catalog/image')->init($addonsproduct, 'small_image')->resize(70).'" width="70" /></div>
                                    <div class="item-info">
                                        <h3>'.$addonsproduct->getName().'</h3>
                                        <p>'.$_coreHelper->currency($addonsproduct->getPrice(), true, FALSE).'</p>
                                        <span>+ Add</span>
                                    </div> 
                                    </a>
                                </li>';
	 
			} 
        } 
		#$addonsHtml = "test";
		echo ($addonsHtml);
		//TODO: check error;
		die();
    }
 
    
    public function IndexAction() {   
        $productid  = $this->getRequest()->getParam('pid');
        $_product=$this->getProduct($productid);
        $layout = Mage::getSingleton('core/layout');
        $reviewBlockS = $layout->createBlock('review/helper')->getSummaryHtml($_product, false, true);
        $qtyoption="";
        $qtyStock = (int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
        for ($i=1;$i<11;$i++){
            $qtyoption.="<option value='".$i."'>".$i."</option>";
            if($qtyStock==$i||$i>10) break;
        }
        if(!$_product->isSaleable()||$qtyStock<1) $qtyoption="<option value='0'>0</option>";
        //http://t5.topbuy.com/checkout/cart/add/product/1509/ 
        $ajaxProductHtml = "<div class='shadow black'></div><div class='shadowbox-pos' style='top: 126px;' ><div id='shadowbox-wrapper-c' class='shadowbox-wrapper'><ul id='topbuy-quickview-tab'><li class='selected' title='topbuy-quickview-overview'>Overview</li><li title='topbuy-quickview-description'>Description</li><li title='topbuy-quickview-photo'>Photos</li><div class='clear'></div></ul>";//<li title='topbuy-quickview-review'>Reviews</li>
        $ajaxProductHtml .= "<div id='topbuy-quickview-wrapper'><div id='topbuy-quickview-overview' class='topbuy-quickview-block' style='display: block; '>";
        $ajaxProductHtml .="      <img src='".$_product->getImageUrl()."' width='250' height='250' >";
        $ajaxProductHtml .="      <div class='qv-prd-info'>";
        $ajaxProductHtml .="        <h2>".$_product->getName()."</h2>";
        if ($reviewBlockS) {$ajaxProductHtml .= $reviewBlockS;}
        else {$ajaxProductHtml .= "No Reviews";}
        //$ajaxProductHtml .="        <p class='rating'><span style='width:80%'></span></p>";
        //$ajaxProductHtml .="        <p class='review'>( <a href='' target='_blank'>".$reviewBlock." Reviews</a> )</p>";
        $ajaxProductHtml .="        <div class='clear'></div>";
        $ajaxProductHtml .="        <p class='price'>$".number_format($_product->getPrice(),2)."</p>";
        $ajaxProductHtml .="        <p class='wishlist'><span>+</span> <a href='".Mage::getBaseUrl()."wishlist/index/add/product/".$_product->getId()."' target='_blank'>Add to Wishlist</a></p>";
        $ajaxProductHtml .="        <div class='clear'></div>";
        $ajaxProductHtml .="        <p class='reward'>Cash Price: $".number_format($_product->getPrice()*0.99,2)." ( <a href='' target='_blank'>?</a> )&nbsp;&nbsp;&nbsp;&nbsp;Earn <strong>".Mage::helper('rewardpoints/data')->getProductPoints($_product, true)." Reward Points</strong></p>";

        if($_product->isSaleable()&&$qtyStock>0)   {$ajaxProductHtml .="<p class='stock in-stock'>In Stock</p>";}
            elseif($qtyStock<10&&$qtyStock>0)       {$ajaxProductHtml .="<p class='stock limit-stock'>Almost Sold Out!</p>";}
            elseif($qtyStock<1)                    {$ajaxProductHtml .="<p class='stock out-stock'>Out of Stock</p>";}
        if(str_replace(' ','',$_product->getProductlocation())=="au") {
            $ajaxProductHtml .="<p class='shipping-location local'>Local Shipping</p>";} else {
            $ajaxProductHtml .="<p class='shipping-location international'>International Shipping*</p>";}
        $ajaxProductHtml .="        <div class='clear'></div>";
        $ajaxProductHtml .="        <p class='eta'>Estimated Time of Arrival: ".$_product->getEta()."</p>";
//        $ajaxProductHtml .="<form action='".Mage::getBaseUrl().'checkout/cart/add/product/'.$_product->getId()."' method='post' name='qaddtocart' >";
//        $ajaxProductHtml .="        <p class='opt qty'>";
//        $ajaxProductHtml .="        <label>Quantity:</label>";
//        $ajaxProductHtml .="        <select name='qty' id='qty' title='Qty' class='input-text qty'>".$qtyoption;
//        $ajaxProductHtml .="        </select>";
//        $ajaxProductHtml .="      </p>";
//$ajaxProductHtml .="      <p class='opt extra'>";
//$ajaxProductHtml .="        <label>Extra:</label>";
//$ajaxProductHtml .="        <select>";
//$ajaxProductHtml .="          <option selected=''>-- Select --</option>";
//$ajaxProductHtml .="          <option>Priority Service $3.00</option>";
//$ajaxProductHtml .="          <option>1 Year Ext. Warranty $100.00</option>";
//$ajaxProductHtml .="          <option>1.5 Years Ext. Warranty $150.00</option>";
//$ajaxProductHtml .="          <option>3 Years Ext. Warranty $300.00</option>";
//$ajaxProductHtml .="        </select>";
//$ajaxProductHtml .="      </p>";
//$ajaxProductHtml .="      <div class='clear'></div>";
//$ajaxProductHtml .="      <!-- add to cart btns -->";
//$ajaxProductHtml .="      <a class='btn paypal'><span>One-Click Checkout</span></a>";
		$ajaxProductHtml .="      <div class='clear'></div>";
    if($_product->isSaleable()||$qtyStock>0){
        $ajaxProductHtml .="      <a title='Add to Cart' id='quick-to-cart' value='".$_product->getId()."' class='btn cart'>Add to Cart</a>";
    } else {
//        $ajaxProductHtml .="      <a href='".Mage::getBaseUrl()."newsletternotify/index/notify?".$_product->getId()."=".urlencode(Mage::helper("core/url")->getCurrentUrl())." class='btn soldout'>Sold Out, Notify Me</a>";
        $ajaxProductHtml .="      <a href='javascript:void(0);' class='btn soldout'>Sold Out</a>";
    }
        $ajaxProductHtml .="      <div class='clear'></div>";
        $ajaxProductHtml .="      </div>";
        $ajaxProductHtml .="      <div class='clear'></div>";
        $ajaxProductHtml .="    </div>";
//        $ajaxProductHtml .="</form>";
        $ajaxProductHtml .="    <div id='topbuy-quickview-description' class='topbuy-quickview-block' style='display: none; '>";
        $ajaxProductHtml .=         $_product->getDescription();
        $ajaxProductHtml .="    </div>";
//            <!-- photos -->";
        $images=$_product->getMediaGalleryImages();
        $i=1;
        $ajaxProductHtml .="    <div id='topbuy-quickview-photo' class='topbuy-quickview-block' style='display: none; '>";
        foreach($images as $image){
            if(file_exists($image->getPath())){ 
                if($i==1) {
                    $ajaxProductHtml .="      <img class='photo-display' src='".$image->getUrl()."' width='500' height='500' >";
                    $ajaxProductHtml .="      <div class='thumbnails'>";
					$ajaxProductHtml .="      <ul class='topbuy-quickview-thumbnail-carousel topbuy-jcarousel-skin-quickview-thumbnail'>";
                    $ajaxProductHtml .="  <li><img src='".$image->getUrl()."' width='78'></li>";
                } 
				
				else {
					  $ajaxProductHtml .="  <li><img src='".$image->getUrl()."' width='78'></li>";
					}
            }
            $i++;
        }
	
        $ajaxProductHtml .="          </ul></div> <div class='clear'></div> </div>";

//      <!-- reviews -->";
//        $ajaxProductHtml .="    <div id='topbuy-quickview-review' class='topbuy-quickview-block' style='display: none; '>";
//        $ajaxProductHtml .= $layout->createBlock('review/helper')->getSummaryHtml($_product, 'detail', false);
//        $update_manager = $layout->getUpdate();			
//        $update_manager->addUpdate('<block type="review/product_view_list" name="root" output="toHtml" template="review/product/view/list.phtml" />');
//        $layout->generateXml();
//        $layout->generateBlocks();			
//        $ajaxProductHtml .= $layout->setDirectOutput(true)->getOutput()->toHtml();
//        $ajaxProductHtml .="  </div>";
        $ajaxProductHtml .=" <div class='shadowbox-close'>CLOSE</div>   </div>";
       
        $ajaxProductHtml .="</div></div></div>";
        echo $ajaxProductHtml;
	//TODO: check error;
	die();
//        print_r($_product);
    }
    
    public function getProduct($productid) {
        if (!Mage::registry('product')) {// && $this->getProductId()
            $product = Mage::getModel('catalog/product')->load($productid);
            Mage::register('product', $product);
        }
        return Mage::registry('product');             
    }
    
    public function PostageAction() {
        $postcode  = (int)$this->getRequest()->getParam('postcode');
        $postweight  = $this->getRequest()->getParam('postweight');
        $postage=Mage::getModel('matrixrate/matrixrate')->getCollection();
        $postage->getSelect()->where('dest_zip <=?', $postcode)->where('dest_zip_to >=?', $postcode)
                ->where('condition_from_value <=?', $postweight)->where('condition_to_value >=?', $postweight);
        $postagePrice="";
        foreach ($postage as $pa){
            $postagePrice = $pa->getPrice()+($postweight*$pa->getCost());
        }
        if($postagePrice=="") {echo $postagePrice="Unknown!";}
        else {echo "$".number_format($postagePrice,2);}
        return;
    }
    
    public function cartPostageAction() {
        $postcode  = (int)$this->getRequest()->getParam('postcode');
        $session = Mage::getSingleton('customer/session');
        $session->setPostcode($postcode);
        Mage::getModel('matrixrate/carrier_matrixrate')->cartPostage($postcode);
//        Mage::log("~~~~~~cartPostageAction~~~~~~~~".$postcode);
        $this->_redirect('checkout/cart');
    }
     
}
