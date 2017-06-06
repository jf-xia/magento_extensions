<?php
class FourTell_Recommend_Block_Widget
    extends Mage_Core_Block_Abstract
    implements Mage_Widget_Block_Interface
{
    protected function _toHtml()
    {
    	// If extension is not enabled then return
    	if (Mage::getStoreConfig('recommend/config/enabled') != "1")
			return '';
		
    	$maxProducts = 5;
		
    	// Load the 4-Tell Recommnedation class
		require_once("FourTell/Recommend/Block/Recommend.php");
		$rec = new Recommender();
		
		// Get the current product being viewed from the registry
		$product = Mage::registry('product');
		
		$pId = "";
		try {
			$pId = $product->getData('entity_id');
		} catch (Exception $e) {
		}
		
		$cartIds = implode(",", Mage::helper('checkout/cart')->getCart()->getProductIds());

		$page = "Unknown";
		
		// Check for search results page
		if (strtolower(Mage::app()->getRequest()->getRouteName()) == "catalogsearch") {
			// Get the product currently being viewed, this might not be all
			// products, it could be the products of the current page
			$sp = Mage::registry('current_layer')->getProductCollection();
			
			// Get up to the first 5 products to send to the 4-Tell service
			$result = array();
			foreach($sp as $p) {
				if (count($result) < $maxProducts) {
					$result[] = $p->getId(); 
				}
			}
			
			$param1 = implode(",", $result);
			$param2 = $cartIds;
			$page = "Search";
			
		// Check for category pages
		} else if (strtolower(Mage::app()->getRequest()->getRouteName()) == "catalog") {
			// Get the product currently being viewed, this might not be all
			// products, it could be the products of the current page
			$currentCategory = Mage::registry('current_category');
			if (is_object($product)) {
			
				$param1 = $pId;
				$param2 = $cartIds;
				$page = "Product";
			
			} else if (is_object($currentCategory)) {
				$collection = $currentCategory->getProductCollection();

				// Get up to the first 5 products to send to the 4-Tell service
				$result = array();
	    		foreach ($collection as $product) {
	    			if (count($result) < $maxProducts) {
		    	  		$result[] = $product->getId();
	    			}
				}

				$param1 = implode(",", $result);
				$param2 = $cartIds;
				$page = "Category";
	    	} else {
				$param1 = "";
				$param2 = $cartIds;
			}
			
		// Check for checkout pages
		} else if (strtolower(Mage::app()->getRequest()->getRouteName()) != "checkout") {
			
			$param1 = $cartIds;
			$param2 = "";
			$page = "Checkout";
			
		// Default is a product page
		} else {
			
			$param1 = $pId;
			$param2 = $cartIds;
			$page = "Product";
			
		}
		
		$ids = 	$rec->getRecommendations(
						$param1,
						$param2, 
						"",
						Mage::getSingleton('customer/session')->getCustomerId(),
						$this->getData('recommendation_type'),
						$this->getData('num_recommendations')
				);
		
		if (!is_array($ids) || empty($ids)) {
			return "";
		}
		
		// Load each product that came from 4-Tell and add to array
		$collection = array();
		foreach($ids as $id) {
			$collection[] = Mage::getModel('catalog/product')->load($id);
		}

		// Default 4-Tell CSS styles, the name of this  
		// class can be overridden in the widget options
		$str = 
		"
			<style>
				.fourtell_widget {
					clear: both; 
					width: 470px !important;
					height: 210px;
					border: 1px solid #E4E0D5;
				}
				
				.fourtell_widget .best-selling {
					padding-left: 5px;
				}
				
				.fourtell_widget .widget_product {
					float: left; 
					clear: none; 
					width: 140px; 
					height: 195px; 
					padding: 10px 5px 0 5px; 
					border: 1px solid #E5DCC3; 
					background: #ffffff;
				}
				
				.fourtell_widget .widget_product a {
					margin-left: 0px; 
					border-color: #E5DCC3;
					color: #1E7EC8;
    				text-decoration: underline;
				}
				
				.fourtell_widget .widget_product a img {
					padding: 10px; 
					margin-bottom: 10px; 
					border: 0px solid #E5DCC3; 
					height: 75px; 
					width: 75px; 
					background-color: #ffffff;
				}
				
				.fourtell_widget .widget_product h3 {
					height: 50px;
					overflow: hidden;
				}
				
				.fourtell_widget .widget_product h3 a {
					color: #0A263C; 
					font-weight: bold;
					font-size: 12px;
				}
				
				.fourtell_widget .widget_product .powered_by {
					float: left; 
					clear: both; 
					font-size: smaller;
				}
				
				.fourtell_widget .widget_product .powered_by a {
					color: #000000;
				}
			</style>
		";

		// Check to see if the CSS class was overridden
		$css_class = "fourtell_widget";
		if ($this->getData('cssclass') != "")
			$css_class = $this->getData('cssclass');
		
		// Generate recommended products html
		$str .= '<div class="' . $css_class . '">';
			if ($this->getData('heading') != "") {
				$str .= '<h3 class="best-selling">';
				$str .= $this->getData('heading');
				$str .= '</h3>';
			}

	        foreach ($collection as $product) {
				$gafunction = "";
				if (trim($this->getData('gafunction')) != "") {
					$gafunction = 'onclick="' .	trim($this->getData('gafunction')) . "('4TellRecs', '" . $product->getId() . "');" . '"';
				}
	
				$str .= '<div class="widget_product">';
				   	$str .= '<a ' . $gafunction . ' href="/index.php/' . $product->getUrlPath() . '">';
						$str .= '<img src="';
						$str .= $product->getThumbnailUrl();
						$str .= '">';
					$str .= '</a>';

					$str .= '<br />';
					$str .= '<h3 class="product-name">';
			        	$str .= '<a ' . $gafunction . ' href="/index.php/' . $product->getUrlPath() . '">';
							$str .= $product->getData("name");
						$str .= '</a>';
					$str .= '</h3>';

					$str .= '<div class="price-box">';
						$str .= '<span class="regular-price">';
							$str .= '<span class="price">';
								$str .= Mage::helper('core')->currency($product->getData("price"));
							$str .= '</span>';
						$str .= '</span>';
					$str .= '</div>';
				$str .= '</div>';
			}
			$str .= '<div class="powered_by">';
			$str .= '<a href="http://www.4-tell.com" target="_blank">Powered by 4-Tell</a>';
			//$str .= '[' . Mage::app()->getRequest()->getRouteName() . ']';
			$str .= '</div>';
		$str .= '</div>';
        
		// Return the html string
		return $str;
    }
}
