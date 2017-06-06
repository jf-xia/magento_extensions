<?php

/*
 * BrainSINS' Magento Extension allows to integrate the BrainSINS
* personalized product recommendations into a Magento Store.
* Copyright (c) 2011 Social Gaming Platform S.R.L.
*
* This file is part of BrainSINS' Magento Extension.
*
*  BrainSINS' Magento Extension is free software: you can redistribute it
*  and/or modify it under the terms of the GNU General Public License
*  as published by the Free Software Foundation, either version 3 of the
*  License, or (at your option) any later version.
*
*  Foobar is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
*  Please do not hesitate to contact us at info@brainsins.com
*
*/

/*
 * This block is attached to the footer of all front-end pages
*/

class Brainsins_Recsins_Block_Recsins extends Mage_Core_Block_Abstract {

	//private $dev = true;
	private $dev = false;

	protected function _construct() {

		$userId = Mage::getSingleton('customer/session')->getCustomer()->getId();

		if (!$userId) {
			if (array_key_exists('coId', $_COOKIE)) {
				$userId = $_COOKIE['coId'];
			} else {
				$userId = '0';
			}
		}
	}

	public function _prepareLayout() {
		return parent::_prepareLayout();
	}

	public function _loadCache() {
		//always force to reload cache
		return false;
	}

	public function getRecsins() {
		if (!$this->hasData('recsins')) {
			$this->setData('recsins', Mage::registry('recsins'));
		}
		return $this->getData('recsins');
	}

	public function getUserId() {

		$userId = Mage::getSingleton('customer/session')->getCustomer()->getId();
		if (!$userId) {
			if (array_key_exists('coId', $_COOKIE)) {
				$userId = $_COOKIE['coId'];
			} else {
				$userId = '0';
			}
		}
		return $userId ? $userId : '0';
	}

	public function getCustomerId() {
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		if (isset($customer)) {
			return Mage::getSingleton('customer/session')->getCustomer()->getId();
		} else {
			return "";
		}
	}

	public function getCustomerEmail() {
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		if (isset($customer)) {
			return Mage::getSingleton('customer/session')->getCustomer()->getEmail();
		} else {
			return "";
		}
	}

	private function getTrackerUrl() {
		if ($this->dev) {
			return "http://dev-tracker.brainsins.com/bstracker.js";
		} else {
			return "http://tracker.brainsins.com/bstracker.js";
		}
	}

	private function getRecommenderUrl() {
		if ($this->dev) {
			return "http://dev-recommender.brainsins.com/bsrecwidget.js";
		} else {
			return "http://recommender.brainsins.com/bsrecwidget.js";
		}
	}

	public function _toHtml() {

		$enabled = Mage::getStoreConfig('brainsins/BS_ENABLED');
		if ($enabled !== '1') {
			return "";
		}

		$trackerUrl = $this->getTrackerUrl();
		$recUrl = $this->getRecommenderUrl();
		$key = Mage::getStoreConfig('brainsins/BSKEY');
		$session = Mage::getSingleton("core/session", array("name" => "frontend"));

		$recScript = "";

		if (!$key || $key == '') {
			return "";
		}

		$html = "";
		$url = Mage::helper('core/url')->getCurrentUrl();

		$html .= '<script type="text/javascript" src="' . $trackerUrl . '"></script>' . PHP_EOL;
		$html .='<script type="text/javascript"> try{ var BrainSINSTracker = BrainSINS.getTracker( "' . $key . '");} catch (err) { }</script>' . PHP_EOL;

		$recommendersSccript = '<script type="text/javascript" src="' . $recUrl . '">';

		$page = Mage::app()->getFrontController()->getRequest()->getRouteName();




		$script = "<script type='text/javascript'>" . PHP_EOL;

		//captured events have set flags

		$loginFlag = $session->getData("recsins_login");
		$logoutFlag = $session->getData("recsins_logout");
		$cartFlag = $session->getData("recsins_cart");
		$checkoutSuccessFlag = $session->getData("recsins_checkout_success");

		$isValidUl = array_key_exists('ul', $_COOKIE) && $_COOKIE['ul'];

		if ($this->getCustomerId() && (!$isValidUl || ($loginFlag && $loginFlag == '1'))) {
			$isNewUser = Mage::getModel("recsins/recsins")->checkNewUser($this->getCustomerId(), $this->getCustomerEmail());

			if (!$isNewUser) {
				$script .= "BrainSINSTracker.trackUserLoggedIn(" . $this->getCustomerId() . ");" . PHP_EOL;
			}
			// if it is a new user, login is performed automatically in the checkNewUser method
		}

		if (!$this->getCustomerId() && $isValidUl) {
			$script .= "if (BrainSINSTracker.isLogged()) { BrainSINSTracker.trackUserLoggedOut();}" . PHP_EOL;
		}

		$userId = $this->getUserId();

		//end captured events

		if ($page == 'cms') {
			$pageId = Mage::getSingleton('cms/page')->getIdentifier();
			$current_page = "cms->" . $pageId;
			$url = Mage::helper('core/url')->getCurrentUrl();

			if ($pageId == 'home') {
				$recScript = $this->getJSRecommendations('brainsins/BS_HOME_RECOMMENDER', 'home_recommendations', $userId, null);
			}
		} else if ($page == 'catalog') {

			$product = Mage::registry('current_product');
			$productId = $product ? $product->getId() : null;
			if ($product) {

				$store = Mage::app()->getStore();
				$url = $product->getUrlModel()->getUrl($product, array('_ignore_category' => true)) . "?___store=" . Mage::app()->getStore()->getCode();

				$recScript = $this->getJSRecommendations('brainsins/BS_PRODUCT_RECOMMENDER', 'product_recommendations', $userId, $productId);
			} else {
				$current_page = "into catalog but no product";
			}
		} else if ($page == "checkout") {
			$request = Mage::app()->getFrontController()->getRequest();

			if ($request->getControllerName() == "cart") {
				$recScript = $this->getJSRecommendations('brainsins/BS_CART_RECOMMENDER', 'cart_recommendations', $userId, null);
			} else if ($request->getControllerName() == "onepage" || $request->getControllerName() == "multishipping") {
				if ($request->getActionName() == "success") {
					$recScript = $this->getJSRecommendations('brainsins/BS_CHECKOUT_RECOMMENDER', 'checkout_recommendations', $userId, null);
				}
			}
		}

		//track page url

		$product = Mage::registry('current_product');
		if ($product) {
			$script .= 'BrainSINSTracker.trackProductview("' . $product->getId() . '");' . PHP_EOL;
		} else {
			$script .= 'BrainSINSTracker.trackPageview("' . $url . '");' . PHP_EOL;
		}

		if ($cartFlag && $userId) {
			$cart = Mage::helper('checkout/cart')->getCart();
			$quote = $cart->getQuote();
			$recsinsModel = Mage::getModel("recsins/recsins");
			$cartUpload = $recsinsModel->uploadQuotes(array($quote), $userId);
		}

		if ($checkoutSuccessFlag && $userId) {
			$recsinsModel = Mage::getModel("recsins/recsins");
			$lastOrder = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
			$recsinsModel->trackCheckoutBegin($userId);
			$recsinsModel->trackCheckoutSuccess($lastOrder, $userId);
		}

		// reset flags

		$session->setData("recsins_login", null);
		$session->setData("recsins_logout", null);
		$session->setData("recsins_cart", null);
		$session->setData("recsins_checkout_success", null);


		$script .= "</script>" . PHP_EOL;

		$html .= $script;

		if ($recScript !== "") {
			$html .= $recScript;
		}

		return $html;
	}

	public function getJSRecommendations($placeKey, $divId, $userId, $productId) {

		$key = Mage::getStoreConfig('brainsins/BSKEY');

		$recommenderId = Mage::getStoreConfig($placeKey);

		if (!isset($recommenderId) || !$recommenderId) {
			return "";
		}

		if (!isset($productId)) {
			$productId = 0;
		}

		$prodId = $productId ? "'" . $productId . "'" : 'null';

		$recUrl = $this->getRecommenderUrl();

		$script = "";

		if (isset($key) && $key != null && isset($recommenderId)) {

			$script = '
<script type="text/javascript" src="' . $recUrl . '">
</script>
<script type="text/javascript">
	try{
               var BrainSINSRecommender = BrainSINS.getRecommender( BrainSINSTracker );' . PHP_EOL .
                    'BrainSINSRecommender.loadWidget("' . $recommenderId . '",' . $prodId . ',"' . Mage::app()->getStore()->getCode() . '","' . $divId . '",' . $userId . ');
	}catch(err) { }
</script>
';
		}

		return $script;
	}
}