<?php   
class Topbuy_Ajax_Block_Proadditional extends Mage_Core_Block_Template{   

    public function getProduct(){
        return Mage::registry('product');
    }
    
    public function getProRs($ids="1509,1508,1507,1505") {
        $proTopbuyId = explode(",", $ids);
        $proBuyTogether = array();
        foreach($proTopbuyId as $_itemtid){
            $result_unit = array();
            $productModel = Mage::getModel('catalog/product');
            $product = $productModel->getCollection()->addAttributeToSelect('*')
                    ->addAttributeToFilter('idtbproduct', $_itemtid)->getFirstItem();
            $result_unit['tid'] = $_itemtid;
            $result_unit['mid'] = $product->getId();
            $result_unit['name'] = $product->getName();
            $result_unit['price'] = $product->getPrice();
            $result_unit['url'] = $product->getProductUrl();
            $result_unit['qty'] = (int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();
            $result_unit['img'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product'.$product->getSmallImage();
            array_push($proBuyTogether,$result_unit); 
        }
        return $proBuyTogether;
    }

    public function getBuyTogetherHtml(){
         if($this->getProRs()){
            $selection="";
            $price=0;
            $img="";
            foreach($this->getItems() as $_item){
                if($_item['qty']>0){ 
                    $selection.="<li><input type='checkbox' class='checkbox related-checkbox' id='related-checkbox ".$_item['mid']." ' name='related_products[]' value='".$_item['mid']."' />";
                    $selection.="<label><a href='".$_item['url']."'>".substr($_item['name'],0,100)."...</a><span> ".sprintf("%01.2f", $_item['price'])."</span></label><div class='clear'></div></li>";
                    $selection.="<input type='hidden' name='related-product-price-".$_item['mid']."' value='".sprintf("%01.2f", $_item['price'])."'>";
                }
                if($price==0) {
                    $img.="<li><img src='".$this->helper('catalog/image')->init($_item, 'thumbnail')->resize(60)."' width='60' height='60' alt='".$this->htmlEscape($_item['name'])."' /></li>";
                } else {
                    $img.="<li><span>+</span><img src='".$_item['img']."' width='60' height='60' alt='".$this->htmlEscape($_item['name'])."' /></li>";
                }

                $price += $_item['price'];
            } 
            $prHtml = "<div id='topbuy-rbt'>";
            $prHtml .= "    <h2 class='topbuy-prd-blocktitle'>Frequently Bought Together</h2>";
            $prHtml .= "    <ul id='topbuy-rbt-prd'>";
            $prHtml .= $img;
            $prHtml .= "    </ul>";
            $prHtml .= "    <div class='price'>Price for All Four: <span>".$price."</span></div>";
            $prHtml .= "    <div class='add-to-cart'>";
            $prHtml .= "        <a class='btn'>Add All to Select</a>";
            $prHtml .= "    </div>";
            $prHtml .= "    <div class='clear'></div>";
            $prHtml .= "    <ul id='topbuy-rbt-selection'>";
            $prHtml .= $selection;
            $prHtml .= "    </ul>";
            $prHtml .= "</div>";    
         }
        return $prHtml;
    }
//
//    protected $_mapRenderer = 'msrp_noform';
//
//    protected $_itemCollection;
//    
//    public function getItems()
//    {
//        return $this->_itemCollection;
//    }
//
//    protected function _prepareData()
//    {
//        $product = Mage::registry('product');
//        /* @var $product Mage_Catalog_Model_Product */
//
//        $this->_itemCollection = getProRs($ids);
//
//        return $this;
//    }
//
//    protected function _beforeToHtml()
//    {
//        $this->_prepareData();
//        return parent::_beforeToHtml();
//    }

}