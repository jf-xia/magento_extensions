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

class Brainsins_Recsins_Model_Recsins extends Mage_Core_Model_Abstract {

    private $bsClient;
    //private $dev = true;
    private $dev = false;
    private $server;

    protected function _construct() {
        parent::_construct();
        $this->_init('recsins/recsins');

        $key = Mage::getStoreConfig('brainsins/BSKEY');


        if ($this->dev) {
            $this->server = "dev-api.brainsins.com";
        } else {
            $this->server = "api.brainsins.com";
        }

        $this->bsClient = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));
    }

    public function importRecommenders() {
        $key = Mage::getStoreConfig('brainsins/BSKEY');
        $client = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));

        $recommenders = simplexml_load_string($client->getAllRecommenders());
        $result = false;
        if (isset($recommenders) && isset($recommenders->recommenders)) {
            //delete previous recommenders

            $collection = Mage::getModel('recsins/recommender')->getCollection();

            foreach ($collection as $recModel) {
                $recModel->delete();
            }

            foreach ($recommenders->recommenders->recommender as $recommender) {

                $recModel = Mage::getModel('recsins/recommender');
                $recModel->setRecommender_id($recommender->id_recommender);
                $recModel->setRecommender_name($recommender->name);
                $recModel->setRecommender_page($recommender->page);

                $recModel->save();
            }

            $result = true;
        }
        return $result;
    }

    public function uploadCatalogUsers($pageSize, $lastPage, $firstTransaction = false) {

        $lastCustomerId = 0;

        $key = Mage::getStoreConfig('brainsins/BSKEY');
        $client = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));

        $customers = Mage::getModel("customer/customer")->getCollection()->addAttributeToSelect('*')->setPage($lastPage + 1, $pageSize);

        $result = true;

        foreach ($customers as $customer) {
            $user = Mage::getModel('recsins/user', array('user_id' => $customer->getId(), 'email' => $customer->getEmail()));
            $client->addUser($user);
        }

        $result = $client->sendUsers();
        Mage::getModel('core/config')->saveConfig('brainsins/LAST_PAGE_SENT', $lastPage + 1);
        Mage::getModel('core/config')->saveConfig('brainsins/BS_LAST_CUSTOMER_ID_UPLOADED', $lastCustomerId);

        return $result;
    }

    public function uploadCatalogProducts($pageSize, $lastPage, $firstTransaction = false) {
        $stores = Mage::app()->getStore()->getCollection();

        $key = Mage::getStoreConfig('brainsins/BSKEY');
        $client = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));

        $transactionCounter = 0;

        $result = true;

        //get first store id
        $firstStoreId = $stores->getFirstItem();

        if ($firstStoreId) {
            $firstStoreId = $firstStoreId->getId();
        } else {
            return;
        }

        if (!isset($firstStoreId)) {
            return;
        }

        $client->resetAll();
        $products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')->setPage($lastPage + 1, $pageSize)->addPriceData(null, $firstStoreId);

        foreach ($products as $product) {
            $id = $product->getId();

            $visibility = $product->getVisibility();
            $configurable_product_model = Mage::getModel('catalog/product_type_configurable');
            $parentIdArray = $configurable_product_model->getParentIdsByChild($product->getId());

            if (count($parentIdArray) == 0 && $visibility > 1 && $product->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_ENABLED) {

                $bsProduct = Mage::getModel('recsins/product');


                $bsProduct->setIsMultilanguage(true);

                $names = array();
                $descriptions = array();
                $urls = array();

                $bsProduct->setProduct_id($product->getId());
                $bsProduct->setPrice($product->getPrice());

                if ($product->getPrice() == 0.0 && $product->getTypeId() == "bundle") {
                    list($minPrice, $maxPrice) = $product->getPriceModel()->getPrices($product);
                    $bsProduct->setPrice($minPrice);
                }

                $categoryList = array();
                $categoryIds = $product->getCategoryIds();


                foreach ($categoryIds as $catId) {
                    $categoryList[] = $catId;
                    $category = Mage::getModel('catalog/category')->load($catId);
                    $parents = $category->getParentIds();
                    $categoryList = array_unique(array_merge($categoryList, $parents));
                }

                $bsProduct->setCategories($categoryList);
                $bsProduct->setImage_url(Mage::helper('catalog/product')->getSmallImageUrl($product));

                foreach ($stores as $store) {
                    $storeCode = $store->getCode();
                    $storeId = $store->getId();

                    $product->setStoreId($storeId);
                    $product->load($id);

                    $names[$storeCode] = utf8_decode($product->getName());
                    $descriptions[$storeCode] = utf8_decode($product->getShortDescription());
                    $urls[$storeCode] = $product->getProductUrl();
                }

                $bsProduct->setNames($names);
                $bsProduct->setDescriptions($descriptions);
                $bsProduct->setUrls($urls);

                $client->addProduct($bsProduct);
            } else {
                //this product is a configuration of a more general product(id)
                // or a non visible product
            }
        }
        Mage::getModel('core/config')->saveConfig('brainsins/LAST_PAGE_SENT', $lastPage + 1);
        $result = $client->sendProducts($firstTransaction);

        return $result;
    }

    private function getNextFakeId($lastFakeId) {
        if (!isset($lastFakeId) || $lastFakeId == 0) {
            return 999999999999;
        } else {
            return $lastFakeId - 1;
        }
    }

    public function uploadCatalogQuotes($pageSize, $lastPage, $lastFakeId = 0, $firstTransaction = false) {

        $key = Mage::getStoreConfig('brainsins/BSKEY');
        $client = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));

        $quotes = Mage::getModel('sales/quote')->getCollection()->setPageSize($pageSize)->setCurPage($lastPage + 1); //($lastPage + 1, 1);
        foreach ($quotes as $quote) {

            $quote->load($quote->getId());
            $items = $quote->getAllItems();
            $idCustomer = $quote->getCustomerId();

            if (!isset($idCustomer)) {
                $lastFakeId = $this->getNextFakeId($lastFakeId);
                $idCustomer = $lastFakeId;
            }
            $bsCart = Mage::getModel('recsins/cart');
            $bsCart->setIdCart($quote->getId());
            $bsCart->setIdUser($idCustomer);
            $bsCart->setStartDate($quote->getCreated_at());
            $bsCart->setFinishDate($quote->getUpdated_at());

            $cartItems = array();
            foreach ($items as $item) {

                if ($item->getParentItemId()) {
                    continue;
                }

                //TODO ? $product = $item->getProduct();
                $qty = $item->getQty();
                $price = $item->getPrice();
                $idProduct = $item->getProduct_id();

                if (array_key_exists($idProduct, $cartItems)) {
                    $newQty = $cartItems[$idProduct]['qty'] + $qty;
                    $newPrice = ($cartItems[$idProduct]['price'] * $cartItems[$idProduct]['qty'] + $price * $qty) / $newQty;
                    $cartItems[$idProduct]['qty'] = $newQty;
                    $cartItems[$idProduct]['price'] = $newPrice;
                } else {
                    $cartItems[$idProduct] = array();
                    $cartItems[$idProduct]['qty'] = $qty;
                    $cartItems[$idProduct]['price'] = $price;
                }
            }

            $bsCart->setProducts($cartItems);
            $client->addCart($bsCart);
        }

        Mage::getModel('core/config')->saveConfig('brainsins/LAST_PAGE_SENT', $lastPage + 1);
        Mage::getModel('core/config')->saveConfig('brainsins/BS_LAST_FAKE_ID', $lastFakeId);
        return $client->sendCatalogCarts($firstTransaction);
    }

    public function uploadCatalogOrders($pageSize, $lastPage, $lastFakeId, $firstTransaction = false) {

        $key = Mage::getStoreConfig('brainsins/BSKEY');
        $client = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));

        $orders = Mage::getModel('sales/order')->getCollection()->setPageSize($pageSize)->setCurPage($lastPage + 1);

        foreach ($orders as $order) {

            $order->load($order->getId());
            $idCustomer = $order->getCustomer_id();
            $quoteId = $order->getQuote_id();

            if (!isset($idCustomer)) {
                $idCustomer = $this->getNextFakeId($lastFakeId);
            }

            $bsOrder = Mage::getModel('recsins/order');
            $bsOrder->setIdPurchase($order->getIncrementId());
            $bsOrder->setIdCart($quoteId);
            $bsOrder->setIdUser($idCustomer);
            $bsOrder->setDate($order->getCreated_at());
            $bsOrder->setAmount($order->getSubtotal());

            $bsCart = Mage::getModel('recsins/cart');
            $bsCart->setIdCart($quoteId);
            $bsCart->setIdUser($idCustomer);
            $bsCart->setStartDate($order->getCreated_at());
            $bsCart->setFinishDate($order->getCreated_at());

            $orderItems = array();
            $cartItems = array();

            $items = $order->getAllItems();
            foreach ($items as $item) {

                if ($item->getParentItemId()) {
                    continue;
                }

                $idProduct = $item->getProduct_id();
                $price = $item->getPrice();
                $qty = (int) $item->getQty_ordered();

                if (array_key_exists($idProduct, $orderItems)) {
                    $newQty = $orderItems[$idProduct]['qty'] + $qty;
                    $newPrice = ($orderItems[$idProduct]['price'] * $orderItems[$idProduct]['qty'] + $price * $qty) / $newQty;
                    $orderItems[$idProduct]['qty'] = $newQty;
                    $orderItems[$idProduct]['price'] = $newPrice;
                    $cartItems[$idProduct]['qty'] = $newQty;
                    $cartItems[$idProduct]['price'] = $newPrice;
                } else {
                    $orderItems[$idProduct] = array();
                    $cartItems[$idProduct] = array();
                    $orderItems[$idProduct]['qty'] = $qty;
                    $orderItems[$idProduct]['price'] = $price;
                    $cartItems[$idProduct]['qty'] = $qty;
                    $cartItems[$idProduct]['price'] = $price;
                }
            }

            $bsCart->setProducts($cartItems);
            $bsOrder->setProducts($orderItems);

            $client->addCart($bsCart);
            $client->addOrder($bsOrder);
        }

        $result = $client->sendCatalogCarts();
        $result = $result && $client->sendCatalogPurchases($firstTransaction);

        Mage::getModel('core/config')->saveConfig('brainsins/LAST_PAGE_SENT', $lastPage + 1);
        Mage::getModel('core/config')->saveConfig('brainsins/BS_LAST_FAKE_ID', $lastFakeId);
        return $result;
    }

    public function uploadQuotes($quotes, $userId = null) {

        $key = Mage::getStoreConfig('brainsins/BSKEY');
        $client = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));

        foreach ($quotes as $quote) {

            $orderId = $quote->getOrig_order_id();
            if ($orderId == 0) {
                $orderId = $quote->getReserved_order_id();
            }

            if ($orderId == 0) {
                $items = $quote->getAllItems();

                if (count($items) > 0) {
                    $idCustomer = $userId ? $userId : $quote->getCustomer()->getId();
                    if ($idCustomer) {

                        $cartCustomerId = $userId ? $userId : $quote->getCustomer()->getId();

                        $bsCart = Mage::getModel('recsins/cart');
                        $bsCart->setIdCart($quote->getId());
                        $bsCart->setUserId($cartCustomerId);

                        $bsCart->setStart_date($quote->getCreated_at());
                        $bsCart->setFinish_date($quote->getUpdated_at());
                        $cartItems = array();


                        foreach ($items as $item) {
                            if ($item->getParentItemId()) {
                                continue;
                            }

                            $qty = $item->getQty();
                            $price = $item->getPrice();


                            $product = $item->getProduct();

                            $productId = $product->getId();
                            $configurable_product_model = Mage::getModel('catalog/product_type_configurable');
                            $parentIdArray = $configurable_product_model->getParentIdsByChild($productId);

                            $found = false;

                            foreach ($cartItems as $storedItem) {

                                if ($productId == $storedItem->getProduct_id()) {

                                    $storedQty = $storedItem->getQuantity();
                                    $storedPrice = $storedItem->getPrice();

                                    $newQty = $storedQty + $qty;
                                    $newPrice = ($storedPrice * $storedQty + $price * $qty) / $newQty;

                                    $storedItem->setQuantity($newQty);
                                    $storedItem->setPrice($newPrice);

                                    $found = true;
                                    break;
                                }
                            }

                            if (!$found) {
                                $cartItem = Mage::getModel('recsins/cartProduct');

                                $cartItem->setProduct_id($productId);
                                $cartItem->setQuantity($qty);
                                $cartItem->setPrice($price);

                                $cartItems[] = $cartItem;
                            }
                        }

                        $bsCart->setProducts($cartItems);
                        $client->addCart($bsCart);
                    }
                } else {
                    //empty cart -> nothing to upload
                }
            }
        }
        $result = $client->sendFullCarts();
        return $result;
    }

    public function trackCheckoutBegin($userId) {
        $key = Mage::getStoreConfig('brainsins/BSKEY');
        $client = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));

        $result = $client->beginCheckout($userId);
    }

    public function trackCheckoutSuccess($order, $user) {
        $key = Mage::getStoreConfig('brainsins/BSKEY');
        $client = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));

        $idOrder;

        $total = 0;

        $idsMultipleArray = Mage::getSingleton('core/session')->getOrderIds(true);
        $firstElement = true;

        if ($idsMultipleArray && is_array($idsMultipleArray)) {
            foreach ($idsMultipleArray as $id) {
                if ($firstElement) {
                    $idOrder = $id;
                    $firstElement = false;
                }
                $order = Mage::getModel('sales/order')->loadByIncrementId($id);
                $total += $order->getSubtotal();
            }
        } else {
            $idOrder = $order->getIncrementId();
            $total = $order->getSubtotal();
            //$total = $order->getBase_grand_total();
        }

        $result = $client->endCheckout($idOrder, $user, $total);

        return $result;
    }

    public function checkNewUser($idUser, $email) {
        $lastId = Mage::getStoreConfig('brainsins/BS_LAST_CUSTOMER_ID_UPLOADED');

        if (!$lastId || $idUser > $lastId) {
            Mage::getModel('core/config')->saveConfig('brainsins/BS_LAST_CUSTOMER_ID_UPLOADED', $idUser);
            $key = Mage::getStoreConfig('brainsins/BSKEY');
            $client = Mage::getSingleton("recsins/client", array("server" => $this->server, "storeKey" => $key));
            $user = Mage::getModel('recsins/user', array('user_id' => $idUser, 'email' => $email));

            $client->addUser($user);
            $client->sendUsers();
            $res = $client->sendUserLogin($idUser, $_COOKIE['coId']);

            setcookie("ul", 1);
            setcookie("uId", $idUser);

            return true;
        } else {
            return false;
        }
    }
    
    public function log($message) {
    	$pathToBsLogDir = Mage::getBaseDir('base') . "/errors/bsins";
    	
    	if (isset($pathToBsLogDir) && !is_dir($pathToBsLogDir)) {
    		mkdir($pathToBsLogDir);
    	
    	}
    	
    	if (isset($pathToBsLogDir) && is_dir($pathToBsLogDir)) {
    		$f = fopen ($pathToBsLogDir . "/bslog.txt", "a");
    		if ($f !== false) {
    			fwrite($f, "[" . date('j-m-y H:m:s') . "] -> ");
    			fwrite($f, $message);
    			fwrite($f, PHP_EOL);
    		}
    	}    	
    }

}