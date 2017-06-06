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

class Brainsins_Recsins_Model_Client extends Mage_Core_Model_Abstract {

	private $products;
	private $users;
	private $orders;
	private $carts;
	private $storeKey;
	private $server;

	protected function _construct() {
		$this->products = array();
		$this->users = array();
		$this->orders = array();
		$this->carts = array();
		$this->storeKey = $this->_data['storeKey'];
		$this->server = $this->_data['server'];
	}

	public function addProduct($product) {
		$this->products[] = $product;
	}

	public function addUser($user) {
		$this->users[] = $user;
	}

	public function addOrder($order) {
		$this->orders[] = $order;
	}

	public function addCart($cart) {
		$this->carts[] = $cart;
	}

	public function resetProducts() {
		$this->products = array();
	}

	public function resetUsers() {
		$this->users = array();
	}

	public function resetOrders() {
		$this->orders = array();
	}

	public function resetAll() {
		$this->resetProducts();
		$this->resetUsers();
		$this->resetOrders();
	}

	public function checkResult($res) {
		$xml = simplexml_load_string($res);

		if ($xml && $xml->status && $xml->status == '200') {
			return true;
		} else {
			return false;
		}
	}

	public function sendUsers($firstTransaction = false) {

		if (empty($this->users)) {
			return true;
		}

		$xml = $this->createEmptyXML();
		$entities = $xml->addChild('entities');

		foreach ($this->users as $user) {
			$entity = $entities->addChild('entity');
			$entity->addAttribute('name', 'user');
			$prop = $entity->addChild('property', $user->getUser_id());
			$prop->addAttribute('name', 'iduser');
			$email = $entity->addChild('property', $user->getEmail());
			$email->addAttribute('name', 'email');
		}

		$resourceName = $firstTransaction ? "replace.xml" : "upload.xml";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/entity/' . $resourceName . '?token=' . $this->storeKey);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml", "Accept: application/xml"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $xml->asXML());


		$result = curl_exec($curl);

		curl_close($curl);
		return $this->checkResult($result);
	}

	public function sendProducts($firstTransaction = false) {
				
		if (empty($this->products)) {
			return true;
		}

		$xml = $this->createEmptyXML();
		$entities = $xml->addChild('entities');
		foreach ($this->products as $product) {
			$entity = $entities->addChild('entity');
			$entity->addAttribute('name', 'product');

			$prop = $entity->addChild('property', utf8_encode(htmlspecialchars($product->getProduct_id())));
			$prop->addAttribute('name', 'idproduct');

			if ($product->getIsMultilanguage()) {
				$multiprop = $entity->addChild('multi_property');
				$multiprop->addAttribute('name', 'name');

				foreach ($product->getNames() as $lang_code => $name) {
					$prop = $multiprop->addChild('property', utf8_encode(htmlspecialchars($name, ENT_QUOTES)));

					$prop->addAttribute('lang', $lang_code);
				}

				$multiprop = $entity->addChild('multi_property');
				$multiprop->addAttribute('name', 'description');

				foreach ($product->getDescriptions() as $lang_code => $description) {
					$prop = $multiprop->addChild('property', utf8_encode(htmlspecialchars($description, ENT_QUOTES)));
					$prop->addAttribute('lang', $lang_code);
				}

				$multiprop = $entity->addChild('multi_property');
				$multiprop->addAttribute('name', 'url');

				foreach ($product->getUrls() as $lang_code => $url) {
					$prop = $multiprop->addChild('property', utf8_encode(htmlspecialchars($url)));
					$prop->addAttribute('lang', $lang_code);
				}
			} else {
				$prop = $entity->addChild('property', utf8_encode(htmlspecialchars($product->getNames())));
				$prop->addAttribute('name', 'name');

				$prop = $entity->addChild('property', utf8_encode(htmlspecialchars($product->getDescriptions())));
				$prop->addAttribute('name', 'description');

				$prop = $entity->addChild('property', utf8_encode(htmlspecialchars($product->getUrls())));
				$prop->addAttribute('name', 'url');
			}

			$categories_text = '';
			$categories = $product->getCategories();

			if (isset($categories)) {
				if (is_array($categories)) {
					for ($i = 0; $i < count($categories); $i++) {
						if (is_numeric($categories[$i])) {
							$categories_text .= $categories[$i];
						} else {
							$categoryDump = var_export($categories[$i], true);
							Mage::getModel('recsins/recsins')->log("Category is not numeric. Dump: " . PHP_EOL . $categoryDump);
						}
						if ($categories_text != '' && $i != count($categories) - 1) {
							$categories_text .= ', ';
						}
					}
				} else {
					$categoriesDump = var_export($categories, true);
					Mage::getModel('recsins/recsins')->log("Categories is not array. Dump: " . PHP_EOL . $categoriesDump);
				}
			}

			$prop = $entity->addChild('property', utf8_encode(htmlspecialchars($categories_text)));
			$prop->addAttribute('name', 'categories');

			$prop = $entity->addChild('property', utf8_encode(htmlspecialchars($product->getPrice())));
			$prop->addAttribute('name', 'price');

			$prop = $entity->addChild('property', utf8_encode(htmlspecialchars($product->getImage_url())));
			$prop->addAttribute('name', 'imageurl');
		}

		$resourceName = $firstTransaction ? "replace.xml" : "upload.xml";
		$text = $xml->asXML();
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/entity/' . $resourceName . '?token=' . $this->storeKey);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml", "Accept: application/xml"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $text);

		$result = curl_exec($curl);
		curl_close($curl);
		return $this->checkResult($result);
	}

	public function sendCatalogPurchases($firstTransaction = false) {
		if (empty($this->orders)) {
			return true;
		}

		$xmlPurchases = $this->createEmptyXML();
		$orders = $xmlPurchases->addChild('orders');

		foreach ($this->orders as $order) {

			$idPurchase = $order->getIdPurchase();

			if (!isset($idPurchase)) {
				continue;
			}

			$xml_order = $orders->addChild('order');

			$orderDate = $order->getDate();

			if (!$orderDate) {
				$orderDate = $order->getFinishDate();
			}
			$normalizedDate = $this->normalizeDate($orderDate);

			$xml_order->addChild('amount', $order->getAmount());
			$xml_order->addChild('idBuyer', $order->getIdUser());
			$xml_order->addChild('idPurchase', $order->getIdPurchase());
			$xml_order->addChild('idOrder', $order->getIdCart());
			$xml_order->addChild('startDate', $normalizedDate);
			$xml_order->addChild('finishDate', $normalizedDate);
			$xml_products = $xml_order->addChild('products');

			foreach ($order->getProducts() as $id => $info) {
				$xml_product = $xml_products->addChild('product');
				$xml_product->addChild('date', $normalizedDate);
				$xml_product->addChild('idProduct', $id);
				$xml_product->addChild('price', $info['price']);
				$xml_product->addChild('productType', 'product');
				$xml_product->addChild('quantity', $info['qty']);
			}
		}

		$resourceName = $firstTransaction ? "replace.xml" : "upload.xml";

		$text = $xmlPurchases->asXML();
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/purchase/upload.xml?token=' . $this->storeKey);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml", "Accept: application/xml"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $text);
		$result = curl_exec($curl);

		curl_close($curl);
		return $this->checkResult($result);
	}

	public function sendPurchases($firstTransaction = false) {

		if (empty($this->orders)) {
			return true;
		}

		$xmlPurchases = $this->createEmptyXML();
		$orders = $xmlPurchases->addChild('orders');

		foreach ($this->orders as $order) {

			if (!$order->getCartId()) {
				continue;
			}

			$xml_order = $orders->addChild('order');

			$orderDate = $order->getDate();
			if (!$orderDate) {
				$orderDate = $order->getFinishDate();
			}

			$normalizedDate = $this->normalizeDate($orderDate);

			$xml_order->addChild('amount', $order->getAmount());
			$xml_order->addChild('idBuyer', $order->getUserId());
			$xml_order->addChild('idPurchase', $order->getOrderId());
			$xml_order->addChild('startDate', $normalizedDate);
			$xml_order->addChild('finishDate', $normalizedDate);
			$xml_products = $xml_order->addChild('products');
			$xml_order->addChild('idOrder', $order->getCartId());


			foreach ($order->getProducts() as $product) {
				$xml_product = $xml_products->addChild('product');
				$xml_product->addChild('date', $normalizedDate);
				$xml_product->addChild('idProduct', $product->getProduct_id());
				$xml_product->addChild('price', $product->getPrice());
				$xml_product->addChild('productType', 'product');
				$xml_product->addChild('quantity', $product->getQuantity());
			}
		}

		$resourceName = $firstTransaction ? "replace.xml" : "upload.xml";

		$text = $xmlPurchases->asXML();
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/purchase/' . $resourceName . 'token=' . $this->storeKey);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml", "Accept: application/xml"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $text);
		$result = curl_exec($curl);

		curl_close($curl);
		return $this->checkResult($result);
	}

	public function sendCatalogCarts($firstTransaction = false) {

		if (empty($this->carts)) {
			return true;
		}

		$xml = $this->createEmptyXML();
		$orders = $xml->addChild('orders');

		foreach ($this->carts as $cart) {

			$xml_order = $orders->addChild('order');
			$xml_order->addChild('idBuyer', $cart->getIdUser());
			$xml_order->addChild('idOrder', $cart->getIdCart());

			if ($cart->getStart_date()) {
				$xml_order->addChild('startDate', $this->normalizeDate($cart->getStartDate()));
			} if ($cart->getFinish_date()) {
				$xml_order->addChild('finishDate', $this->normalizeDate($cart->getFinishDate()));
			}

			$xml_products = $xml_order->addChild('products');

			foreach ($cart->getProducts() as $id => $info) {

				$xml_product = $xml_products->addChild('product');

				$xml_product->addChild('date', $this->normalizeDate($cart->getStartDate()));

				$xml_product->addChild('idProduct', $id);
				$xml_product->addChild('productType', 'product');
				$xml_product->addChild('quantity', $info['qty']);
				if (isset($info['price'])) {
					$xml_product->addChild('price', $info['price']);
				}
			}
		}
		$resourceName = $firstTransaction ? "replace.xml" : "upload.xml";
		$text = $xml->asXML();
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/order/upload.xml?token=' . $this->storeKey);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml", "Accept: application/xml"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $text);
		$result = curl_exec($curl);
		curl_close($curl);
		return $this->checkResult($result);
	}

	public function sendFullCarts($firstTransaction = false) {

		if (empty($this->carts)) {
			return true;
		}

		$xml = $this->createEmptyXML();
		$orders = $xml->addChild('orders');

		foreach ($this->carts as $cart) {

			$xml_order = $orders->addChild('order');
			$xml_order->addChild('idBuyer', $cart->getUserId());
			$xml_order->addChild('idOrder', $cart->getIdCart());

			if ($cart->getStart_date()) {
				$xml_order->addChild('startDate', $this->normalizeDate($cart->getStart_date()));
			} if ($cart->getFinish_date()) {
				$xml_order->addChild('finishDate', $this->normalizeDate($cart->getFinish_date()));
			}

			$xml_products = $xml_order->addChild('products');

			$eldestProduct;

			foreach ($cart->getProducts() as $product) {

				$normalizedDate = $this->normalizeDate($product->getDate_added());

				$xml_product = $xml_products->addChild('product');

				if ($product->getDate_added()) {
					$xml_product->addChild('date', $this->normalizeDate($product->getDate_added()));
				} else {
					$xml_product->addChild('date', $this->normalizeDate($cart->getStart_date()));
				}

				$xml_product->addChild('idProduct', $product->getProduct_id());
				$xml_product->addChild('productType', 'product');
				$xml_product->addChild('quantity', $product->getQuantity());
				if ($product->getPrice()) {
					$xml_product->addChild('price', $product->getPrice());
				}
			}
		}
		$resourceName = $firstTransaction ? "replace.xml" : "upload.xml";
		$text = $xml->asXML();

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/order/' . $resourceName . '?token=' . $this->storeKey);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml", "Accept: application/xml"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $text);
		$result = curl_exec($curl);
		curl_close($curl);
		return $this->checkResult($result);
	}

	private function normalizeDate($date) {
		$serverTimezone = new DateTimeZone(date_default_timezone_get());

		$dateOffset = $serverTimezone->getOffset(new DateTime("now", new DateTimeZone('GMT')));
		$unixTime = strtotime($date);
		$normalizedDate = date('Y-m-d H:i:s', $unixTime - $dateOffset);
		return $normalizedDate;
	}

	private function createEmptyXML() {
		$xmlstr = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
        <recsins version='0.1'>
        </recsins>";

		return new SimpleXMLElement($xmlstr);
	}

	private function createOkXML() {
		return "<?xml version='1.0' encoding='UTF-8' standalone='yes'?><recsins version='0.1'><message>Ok</message><status>200</status></recsins>";
	}

	public function getAllRecommenders() {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/recommender/retrieve.xml?token=' . $this->storeKey);
		$timeout = 5;
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml", "Accept: application/xml"));
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	// curl track functions

	public function beginCheckout($idUser) {

		$xml = $this->createEmptyXML();
		$xml_orders = $xml->addChild('orders');
		$xml_order = $xml_orders->addChild('order');
		$xml_order->addChild('idBuyer', $idUser);

		$text = $xml->asXML();

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/purchase/create.xml?token=' . $this->storeKey);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml", "Accept: application/xml"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $text);
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	public function endCheckout($idOrder, $idUser, $amount) {

		$xml = $this->createEmptyXML();

		$orders = $xml->addChild("orders");
		$order = $orders->addChild("order");
		$order->addChild("idPurchase", $idOrder);
		$order->addChild("idBuyer", $idUser);
		$order->addChild("amount", $amount);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/purchase/close/' . $idOrder . '/' . $idUser . '/' . $amount . '.xml?token=' . $this->storeKey);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml", "Accept: application/xml"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "");
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	public function sendUserLogin($idUser, $cookieId) {

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->server . '/RecSinsAPI/api/order/logIn.json?cookieId=' . $cookieId . '&userId=' . $idUser . '&token=' . $this->storeKey);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Accept: application/json"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "");

		$result = curl_exec($curl);
		return $result;
	}

}