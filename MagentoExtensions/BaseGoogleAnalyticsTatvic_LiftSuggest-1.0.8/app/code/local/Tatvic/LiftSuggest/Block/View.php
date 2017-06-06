<?php
/*
* @author Tatvic Interactive
* Email : info@liftsuggest.com
* URL : http://www.liftsuggest.com
* Description : LiftSuggest Recommendations is the module that helps you show recommendations for your products to users/visitors on product pages and/or shopping cart page. This will help in increasing the average order value and conversion rate of your site.
* File : View.php
* @copyright Copyright (C) 20011 Tatvic Interactive - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see license.php
* LiftSuggest Recommendations is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/

class Tatvic_LiftSuggest_Block_View extends Mage_Core_Block_Template {
	protected $_lift_item;
	protected $_lift_item_ub;

	public function Tatvic_LiftSuggest_Block_View($call_from=""){

		$status = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->active;
		$prodpage = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->prodpage;

		$prodpageub= Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->prodpageub;
		$cartpage = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->cartpage;
		if($status==1){
			if($prodpage==1 && $call_from=='Mage_Catalog_Block_Product_View')
			{
				$this->_lift_item = Mage::registry('current_product')->getSku();
			}
			if($prodpageub==1 && $call_from=='Mage_Catalog_Block_Product_View')
			{
				$this->_lift_item_ub = Mage::registry('current_product')->getSku();
			}

			else if($cartpage==1 && $call_from=='Mage_Checkout_Block_Cart')
			{
				$cart = new Mage_Checkout_Model_Cart();
				$cart->init();

				if($cart->getItems()!=null)
					$cartItems = $cart->getItems()->getData();
				else
					$cartItems = array();

				$sku_list = '';
				for($i=0;$i<count($cartItems);$i++):
					if($sku_list == '')
						$sku_list = $cartItems[$i]['sku'];
					else
						$sku_list = $sku_list . "," . $cartItems[$i]['sku'];
				endfor;
				$this->_lift_item = $sku_list;
			}else{
			return false;
			}
		}else{
			return false;
		}
}

	public function showRecommendation()
	{
		$status = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->active;
		if($status==0)
			return false;
		$sku = $this->_lift_item;
		$lifttoken = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->lifttoken;
		$liftuserid = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->liftuserid;
		$reclimit = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->reclimit;
		$liftdomain = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->domain;
		$add_to_cart = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->cartbutton;
		$img_size = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->imgsize;

		$url = "http://www.liftsuggest.com/index.php/rest_c/user/token/$lifttoken/custid/$liftuserid/prodsku/$sku/limit/$reclimit/format/json/domain/$liftdomain";
		
		$curl_ob = curl_init();

		curl_setopt($curl_ob, CURLOPT_URL, $url);
		curl_setopt($curl_ob, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($curl_ob);

		curl_close($curl_ob);

		$result = array();
		$result = json_decode($response,true);
		
		$error = false;
		if(isset($result['error'])){
			$error_msg = "Invalid Request. Please check configuration in Admin Panel : System -> Configuration -> Sales -> Lift Suggest";
			$error = true;
		}
		//Fetch the response into an array
		$reco = array();

		if(is_array($result)&& count($result)>0 && $error==false){

		$sym = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
		foreach ($result as $key=>$value) {
				if(is_array($value) && count($value)>0){
				foreach ($value as $key1 => $value1) {
						if($key1 == "products") {
							$reco = $value1;
						}else  if($key1 == "popular_perc") {
							$view_perc = $value1;
						}
				}
				}
				else
				{
					//echo "<!-- recommendation couldn't be loaded ".$value." -->";
				}
		}
                $view_perc = intval(trim($view_perc,'%'));
                $view_perc = round($view_perc);
                $view_perc = $view_perc . "%";

		$res_reco = array();
			if(!isset($_SESSION['reco_prods']))
			{
                $_SESSION['reco_prods'] = array();
			}
		array_push($res_reco,$view_perc);
		$_helper = $this->helper('catalog/output');
		foreach($reco as $key1=>$value1){
			$prod_sku = $value1['sku'];
			$prod_details = array();

			$collection = Mage::getModel('catalog/product')->loadByAttribute('sku',$prod_sku);
			if($collection==false || $collection==null){
				continue;
			}

			$status = $collection->getStatus();
			if($status == 1):
				$product_id = $collection->getProductId();
				$prod_name = $collection->getName();

				$prod_price = round($_helper->productAttribute($collection, $collection->getPrice(), 'price'), 2);

				$new_link = $this->helper('checkout/cart')->getAddUrl($collection);
				$prod_link = $collection->getProductUrl();
                                $lift_img_src = "";

                                if($img_size == 1) {
                                    $img_src = (string)$this->helper('catalog/image')->init($collection,'small_image');
                                }else if($img_size == 2) {
                                    $img_src = (string)$this->helper('catalog/image')->init($collection,'thumbnail');
                                }else {
                                    $img_src = (string)$this->helper('catalog/image')->init($collection, 'image');
                                }

                                $prod_details["prod_sku"] = $prod_sku;
                                $prod_details["prod_name"] = $prod_name;
                                $prod_details["prod_price"] = $prod_price;
                                $prod_details["prod_link"] = $prod_link;
				if($add_to_cart==1){
                                    $prod_details["prod_cart_link"] = $new_link;
                                }
                                $prod_details["prod_currency"] = $sym;
                                $prod_details["prod_img_path"] = $img_src;

				array_push($res_reco,$prod_details);
				array_push($_SESSION['reco_prods'],$prod_sku); // For GA tracking
			else:
				continue;
			endif;
		}
		return $res_reco;
	}else{
			if($error==true)
				return $error_msg;
			else
				return false;
		}
}




	public function getBoughtProduct()
	{
		$status = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->active;
		if($status==0)
			return false;
		$sku = $this->_lift_item;
		$lifttoken = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->lifttoken;
		$liftuserid = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->liftuserid;
		$reclimit = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->reclimit;
		$liftdomain = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->domain;
		$add_to_cart = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->cartbutton;
		$img_size = Mage::getConfig()->getNode('default/liftsuggest/liftsuggest')->imgsize;

		$url = "http://www.liftsuggest.com/index.php/rest_c_ub/user/token/$lifttoken/custid/$liftuserid/prodsku/$sku/limit/$reclimit/format/json/domain/$liftdomain";

		$curl_ob = curl_init();
		curl_setopt($curl_ob, CURLOPT_URL, $url);
		curl_setopt($curl_ob, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($curl_ob);

		curl_close($curl_ob);

		$result = array();
		$result = json_decode($response,true);

		$error = false;
		if(isset($result['error'])){
			$error_msg = "Invalid Request. Please check configuration in Admin Panel : System -> Configuration -> Sales -> Lift Suggest";
			$error = true;
		}
		//Fetch the response into an array
		$reco = array();

		if(is_array($result)&& count($result)>0 && $error==false){

		$sym = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();

		        $value = array();

				foreach ($result as $rec)
				{
					for($i=0;$i<count($rec['products']);$i++)
					{

						$value[$i] = $rec['products'][$i]['sku'][0];
					}
				}
			$value=array_unique($value);

		$res_reco = array();
			if(!isset($_SESSION['ub_reco_prods']))
			{
                $_SESSION['ub_reco_prods'] = array();
			}

		$_helper = $this->helper('catalog/output');
		foreach($value as $key1=>$value1)
		{
			$prod_sku = $value1;
			$prod_details = array();

			$collection = Mage::getModel('catalog/product')->loadByAttribute('sku',$prod_sku);
			if($collection==false || $collection==null){
				continue;
			}

			$status = $collection->getStatus();
			if($status == 1):
				$product_id = $collection->getProductId();
				$prod_name = $collection->getName();

				$prod_price = round($_helper->productAttribute($collection, $collection->getPrice(), 'price'), 2);

				$new_link = $this->helper('checkout/cart')->getAddUrl($collection);
				$prod_link = $collection->getProductUrl();
                                $lift_img_src = "";

                                if($img_size == 1) {
                                    $img_src = (string)$this->helper('catalog/image')->init($collection,'small_image');
                                }else if($img_size == 2) {
                                    $img_src = (string)$this->helper('catalog/image')->init($collection,'thumbnail');
                                }else {
                                    $img_src = (string)$this->helper('catalog/image')->init($collection, 'image');
                                }

                                $prod_details["prod_sku"] = $prod_sku;
                                $prod_details["prod_name"] = $prod_name;
                                $prod_details["prod_price"] = $prod_price;
                                $prod_details["prod_link"] = $prod_link;
				if($add_to_cart==1){
                                    $prod_details["prod_cart_link"] = $new_link;
                                }
                                $prod_details["prod_currency"] = $sym;
                                $prod_details["prod_img_path"] = $img_src;

				array_push($res_reco,$prod_details);
				array_push($_SESSION['ub_reco_prods'],$prod_sku); // For GA tracking
			else:
				continue;
			endif;
		}

		return $res_reco;
	}else{
			if($error==true)
				return $error_msg;
			else
				return false;
		}
}


public function toOptionArray($typ=null)
  {
    return array(
      array('value' => 0, 'label' => "Base Image"),
      array('value' => 1, 'label' => "Small Image"),
      array('value' => 2, 'label' => "Thumbnail Image"),
    );
  }

	protected function _toHtml() {
		echo $html;
	}
}
?>