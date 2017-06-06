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


function bsErrorHandler($errno, $errstr, $errfile, $errline) {
	Mage::getModel('recsins/recsins')->log($errno . " | " . $errstr . " | " . $errfile . " at line " . $errline);
	$mageResponse =  mageCoreErrorHandler($errno, $errstr, $errfile, $errline);
	if (isset($mageResponse) && $mageResponse === false) {
		Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('recsins')->__('Catalog upload aborted due to unexpected error<br>See [storeUrl]/erros/bsins/bsinslog for details'));
		return false;
	} else {
		return $mageResponse;
	}
}

class Brainsins_Recsins_Adminhtml_RecsinsController extends Mage_Adminhtml_Controller_Action {

	protected function _initAction() {
		$this->loadLayout()
		->_setActiveMenu('recsins/items')
		->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

		return $this;
	}

	public function indexAction() {
		$this->_initAction()
		->renderLayout();
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		$model = Mage::getModel('recsins/recsins')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('recsins_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('recsins/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('recsins/adminhtml_recsins_edit'))
			->_addLeft($this->getLayout()->createBlock('recsins/adminhtml_recsins_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('recsins')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction() {
		$this->_forward('edit');
	}

	public function saveAction() {

		$old_error_handler = set_error_handler("bsErrorHandler");

		try {

			//throw new Exception("BSException: extrange category");

			$data = $this->getRequest()->getPost();

			if ($data) {

				$key = $data['bskey_text'];

				if (isset($key)) {
					Mage::getModel('core/config')->saveConfig('brainsins/BSKEY', $key);
				}

				$enabled = $data['bsenableoptions'];

				if ($enabled === '0') {
					Mage::getModel('core/config')->saveConfig('brainsins/BS_ENABLED', '0');
				} else {
					Mage::getModel('core/config')->saveConfig('brainsins/BS_ENABLED', '1');
				}


				$homeRecommender;
				$productRecommender;
				$cartRecommender;
				$checkoutRecommender;

				if (array_key_exists('bshome_recommenders', $data)) {
					$homeRecommender = $data['bshome_recommenders'];
				} else {
					$homeRecommender = 0;
				}

				if (array_key_exists('bsproduct_recommenders', $data)) {
					$productRecommender = $data['bsproduct_recommenders'];
				} else {
					$productRecommender = 0;
				}

				if (array_key_exists('bscart_recommenders', $data)) {
					$cartRecommender = $data['bscart_recommenders'];
				} else {
					$cartRecommender = 0;
				}

				if (array_key_exists('bscheckout_recommenders', $data)) {
					$checkoutRecommender = $data['bscheckout_recommenders'];
				} else {
					$checkoutRecommender = 0;
				}

				if (isset($homeRecommender) && $homeRecommender !== null) {
					Mage::getModel('core/config')->saveConfig('brainsins/BS_HOME_RECOMMENDER', $homeRecommender);
				}

				if (isset($productRecommender) && $productRecommender !== null) {
					Mage::getModel('core/config')->saveConfig('brainsins/BS_PRODUCT_RECOMMENDER', $productRecommender);
				}

				if (isset($checkoutRecommender) && $checkoutRecommender !== null) {
					Mage::getModel('core/config')->saveConfig('brainsins/BS_CHECKOUT_RECOMMENDER', $checkoutRecommender);
				}

				if (isset($cartRecommender) && $cartRecommender !== null) {
					Mage::getModel('core/config')->saveConfig('brainsins/BS_CART_RECOMMENDER', $cartRecommender);
				}

				$pageSizeText = $data['bsadvanced'];
				$pageSize = 50;

				if (isset($pageSizeText) && $pageSizeText !== null) {
					Mage::getModel('core/config')->saveConfig('brainsins/BS_PAGE_SIZE', $pageSizeText);
					if ($pageSizeText == 'page1') {
						$pageSize = 1;
					}
					if ($pageSizeText == 'page10') {
						$pageSize = 10;
					}
					if ($pageSizeText == 'page20') {
						$pageSize = 20;
					}
					if ($pageSizeText == 'page50') {
						$pageSize = 50;
					}
					if ($pageSizeText == 'page100') {
						$pageSize = 100;
					}
					if ($pageSizeText == 'page200') {
						$pageSize = 200;
					}
				}

				$importConfig = $data['import_config'];
				if (isset($importConfig) && $importConfig == '1') {

					$recsins = Mage::getSingleton('recsins/recsins');
					$result = $recsins->importRecommenders();
					if ($result === true) {
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('recsins')->__('Recommenders imported sucssessfully'));
					} else {
						Mage::getSingleton('adminhtml/session')->addError(Mage::helper('recsins')->__('Error while importing recommenders'));
					}
					$this->_redirect('*/*/');
				}

				$uploadCatalog = $data['upload_catalog'];

				if (isset($uploadCatalog) && $uploadCatalog == 'upload') {
					$this->uploadCatalog($pageSize);
					$this->_redirect('*/*/');
					return;
				} else if (isset($uploadCatalog) && $uploadCatalog == 'uploading') {
					$this->uploadingCatalog($pageSize);
					$this->_redirect('*/*/');
					return;
				} else if (isset($uploadCatalog) && $uploadCatalog == 'abort') {
					Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('recsins')->__('Catalog upload aborted'));
					$this->_redirect('*/*/');
					return;
				}
			}

		} catch (Exception $e) {
			Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
			Mage::getModel('recsins/recsins')->log($e);
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('recsins')->__('Catalog upload aborted due to unexpected exception<br>See [storeUrl]/erros/bsins/bsinslog for details'));
		}
		
		$this->_redirect('*/*/');


		return;
	}

	public function deleteAction() {
		$this->_redirect('*/*/');
	}

	public function massDeleteAction() {
		$this->_redirect('*/*/index');
	}

	public function massStatusAction() {
		$this->_redirect('*/*/index');
	}

	public function exportCsvAction() {

	}

	public function exportXmlAction() {

	}

	private function uploadCatalog($pageSize) {



		Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '1');
		Mage::getModel('core/config')->saveConfig('brainsins/LAST_PAGE_SENT', '0');
		Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATE', 'CLIENTS');
		Mage::getModel('core/config')->saveConfig('brainsins/FIRST_TRANSACTION', '1');

		$lastPageSent = Mage::getStoreConfig('brainsins/LAST_PAGE_SENT');

		$this->uploadingCatalog($pageSize, 'CLIENTS');

		return;
	}

	private function uploadingCatalog($pageSize, $state = null) {
		 
		$recsins = Mage::getSingleton('recsins/recsins');
		$lastPageSent = Mage::getStoreConfig('brainsins/LAST_PAGE_SENT');

		if (!isset($state) || $state == null) {
			$state = Mage::getStoreConfig('brainsins/UPLOADING_STATE');
		}

		$firstTransaction = Mage::getStoreConfig('brainsins/FIRST_TRANSACTION');
		if (isset($firstTransaction)) {
			$firstTransaction = isset($firstTransaction) && $firstTransaction == '1';
		} else {
			$firstTransaction = false;
		}

		$isFirstUpload = Mage::getStoreConfig('brainsins/BS_FIRST_CATALOG_UPLOAD');
		if (isset($isFirstUpload)) {
			$isFirstUpload = $isFirstUpload == '1';
		} else {
			$isFirstUpload = true;
		}

		$lastFakeId = 0;

		if ($isFirstUpload === true) {
			$lastFakeId = Mage::getStoreConfig('brainsins/BS_LAST_FAKE_ID');
			if (!isset($lastFakeId)) {
				$lastFakeId = 0;
			} else {

			}
		}


		if ($state === 'CLIENTS') {

			$numItems = Mage::getModel('customer/customer')->getCollection()->count();
			$res = $recsins->uploadCatalogUsers($pageSize, $lastPageSent, $firstTransaction);
			$lastPageSent = Mage::getStoreConfig('brainsins/LAST_PAGE_SENT');
			$continue = isset($res) && $res === true && $lastPageSent * $pageSize < $numItems;

			if (!isset($res) || $res === false) {
				$message = $this->getDisplayMessage('CLIENTS', 'ERROR');
				Mage::getSingleton('adminhtml/session')->addError($message);
				Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
			} else if ($continue === true) {
				$percentage = number_format(($lastPageSent * $pageSize * 100) / $numItems, 1);
				$message = $this->getDisplayMessage('CLIENTS', 'UPLOADING', $percentage);
				Mage::getSingleton('adminhtml/session')->addSuccess($message);
				if ($isFirstUpload) {
					Mage::getModel('core/config')->saveConfig('brainsins/FIRST_TRANSACTION', '0');
					Mage::getModel('core/config')->saveConfig('brainsins/BS_LAST_FAKE_ID', '0');
				}
			} else {
				$message = $this->getDisplayMessage('CLIENTS', 'FINISHED');
				Mage::getSingleton('adminhtml/session')->addSuccess($message);
				Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATE', 'PRODUCTS');
				Mage::getModel('core/config')->saveConfig('brainsins/LAST_PAGE_SENT', '0');
				Mage::getModel('core/config')->saveConfig('brainsins/FIRST_TRANSACTION', '1');
			}

			return;
		} else if ($state === 'PRODUCTS') {

			$numItems = Mage::getModel('catalog/product')->getCollection()->count();
			$res = $recsins->uploadCatalogProducts($pageSize, $lastPageSent, $firstTransaction);
			$lastPageSent = Mage::getStoreConfig('brainsins/LAST_PAGE_SENT');
			$continue = isset($res) && $res === true && $lastPageSent * $pageSize < $numItems;

			if (!isset($res) || $res === false) {
				$message = $this->getDisplayMessage('CLIENTS', 'ERROR');
				Mage::getSingleton('adminhtml/session')->addError($message);
				Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
			} else if ($continue === true) {
				$percentage = number_format(($lastPageSent * $pageSize * 100) / $numItems, 1);
				$message = $this->getDisplayMessage('PRODUCTS', 'UPLOADING', $percentage);
				Mage::getSingleton('adminhtml/session')->addSuccess($message);
				if ($firstTransaction) {
					Mage::getModel('core/config')->saveConfig('brainsins/FIRST_TRANSACTION', '0');
				}
			} else {
				$message = $this->getDisplayMessage('PRODUCTS', 'FINISHED');
				Mage::getSingleton('adminhtml/session')->addSuccess($message);
				if ($isFirstUpload === true) {
					Mage::getModel('core/config')->saveConfig('brainsins/LAST_PAGE_SENT', '0');
					Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATE', 'QUOTES');
					Mage::getModel('core/config')->saveConfig('brainsins/FIRST_TRANSACTION', '1');
					Mage::getModel('core/config')->saveConfig('brainsins/BS_LAST_FAKE_ID', '0');
				} else {
					Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
				}
			}
			return;
		} else if ($state === 'QUOTES' && $isFirstUpload === true) {
			$numItems = Mage::getModel('sales/quote')->getCollection()->count();
			$res = $recsins->uploadCatalogQuotes($pageSize, $lastPageSent, $lastFakeId, $firstTransaction);
			$lastPageSent = Mage::getStoreConfig('brainsins/LAST_PAGE_SENT');
			$continue = isset($res) && $res === true && $lastPageSent * $pageSize < $numItems;

			if (!isset($res) || $res === false) {
				$message = $this->getDisplayMessage('CLIENTS', 'ERROR');
				Mage::getSingleton('adminhtml/session')->addError($message);
				Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
			} else if ($continue === true) {
				$percentage = number_format(($lastPageSent * $pageSize * 100) / $numItems, 1);
				$message = $this->getDisplayMessage('QUOTES', 'UPLOADING', $percentage);
				Mage::getSingleton('adminhtml/session')->addSuccess($message);
				if ($isFirstUpload) {
					Mage::getModel('core/config')->saveConfig('brainsins/FIRST_TRANSACTION', '0');
				}
			} else {
				$message = $this->getDisplayMessage('QUOTES', 'FINISHED');
				Mage::getSingleton('adminhtml/session')->addSuccess($message);
				Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATE', 'ORDERS');
				Mage::getModel('core/config')->saveConfig('brainsins/LAST_PAGE_SENT', '0');
				Mage::getModel('core/config')->saveConfig('brainsins/FIRST_TRANSACTION', '1');
			}
			return;
		} else if ($state === 'ORDERS' && $isFirstUpload === true) {

			$numItems = Mage::getModel('sales/order')->getCollection()->count();
			$res = $recsins->uploadCatalogOrders($pageSize, $lastPageSent, $lastFakeId, $firstTransaction);
			$lastPageSent = Mage::getStoreConfig('brainsins/LAST_PAGE_SENT');
			$continue = isset($res) && $res === true && $lastPageSent * $pageSize < $numItems;

			if (!isset($res) || $res === false) {
				$message = $this->getDisplayMessage('CLIENTS', 'ERROR');
				Mage::getSingleton('adminhtml/session')->addError($message);
				Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
			} else if ($continue === true) {
				$percentage = number_format(($lastPageSent * $pageSize * 100) / $numItems, 1);
				$message = $this->getDisplayMessage('ORDERS', 'UPLOADING', $percentage);
				Mage::getSingleton('adminhtml/session')->addSuccess($message);
				if ($isFirstUpload) {
					Mage::getModel('core/config')->saveConfig('brainsins/FIRST_TRANSACTION', '0');
				}
			} else {
				$message = $this->getDisplayMessage('ORDERS', 'FINISHED');
				Mage::getSingleton('adminhtml/session')->addSuccess($message);

				Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATE', 'ORDERS');
				Mage::getModel('core/config')->saveConfig('brainsins/LAST_PAGE_SENT', '0');
				Mage::getModel('core/config')->saveConfig('brainsins/FIRST_TRANSACTION', '1');
				Mage::getModel('core/config')->saveConfig('brainsins/BS_FIRST_CATALOG_UPLOAD', "0");
				Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
			}
			return;
		} else {
			Mage::getModel('core/config')->saveConfig('brainsins/UPLOADING_STATUS', '0');
		}

		return;
	}

	private function getDisplayMessage($state, $status, $progress = null) {

		$message = "";

		$isFirstUpload = Mage::getStoreConfig('brainsins/BS_FIRST_CATALOG_UPLOAD');
		$numStages = 2;

		if (isset($isFirstUpload)) {
			$isFirstUpload = $isFirstUpload == '1';
		} else {
			$isFirstUpload = false;
		}

		if ($isFirstUpload) {
			$numStages = 4;
		}

		if ($state == 'CLIENTS') {
			if ($status == 'UPLOADING') {
				$message .= "(1/$numStages) " . Mage::helper('recsins')->__("Uploading Clients") . " : $progress %<br>";
			} else if ($status == 'FINISHED') {
				$message .= "(1/$numStages) " . Mage::helper('recsins')->__("Clients uploaded OK") . "<br>";
				$message .= "(2/$numStages) " . Mage::helper('recsins')->__("Uploading Products") . " : 0.0 %<br>";
			} else if ($status == 'ERROR') {
				$message .= Mage::helper('recsins')->__("Error occurred while uploading Clients") . "<br>";
				$message .= Mage::helper('recsins')->__("Catalog upload aborted") . "<br>";
			}
			return $message;
		} else {
			$message .= "(1/$numStages) " . Mage::helper('recsins')->__("Clients uploaded OK") . "<br>";
		}

		if ($state == 'PRODUCTS') {
			if ($status == 'UPLOADING') {
				$message .= "(2/$numStages) " . Mage::helper('recsins')->__("Uploading Products") . " : $progress %<br>";
			} else if ($status == 'FINISHED') {
				$message .= "(2/$numStages) " . Mage::helper('recsins')->__("Products uploaded OK") . "<br>";
				if ($isFirstUpload) {
					$message .= "(3/$numStages) " . Mage::helper('recsins')->__("Uploading Carts") . " : 0.0 %<br>";
				}
			} else if ($status == 'ERROR') {
				$message .= Mage::helper('recsins')->__("Error occurred while uploading Products") . "<br>";
				$message .= Mage::helper('recsins')->__("Catalog upload aborted") . "<br>";
			}
			return $message;
		} else {
			$message .= "(2/$numStages) " . Mage::helper('recsins')->__("Products uploaded OK") . "<br>";
		}

		if ($isFirstUpload) {

			if ($state == 'QUOTES') {
				if ($status == 'UPLOADING') {
					$message .= "(3/$numStages) " . Mage::helper('recsins')->__("Uploading Carts") . " : $progress %<br>";
				} else if ($status == 'FINISHED') {
					$message .= "(3/$numStages) " . Mage::helper('recsins')->__("Carts uploaded OK") . "<br>";
					$message .= "(4/$numStages) " . Mage::helper('recsins')->__("Uploading Purchases") . " : 0.0 %<br>";
				} else if ($status == 'ERROR') {
					$message .= Mage::helper('recsins')->__("Error occurred while uploading Carts") . "<br>";
					$message .= Mage::helper('recsins')->__("Catalog upload aborted") . "<br>";
				}
				return $message;
			} else {
				$message .= "(3/$numStages) " . Mage::helper('recsins')->__("Carts uploaded OK") . "<br>";
			}

			if ($state == 'ORDERS') {
				if ($status == 'UPLOADING') {
					$message .= "(4/$numStages) " . Mage::helper('recsins')->__("Uploading Purchases") . " : $progress %<br>";
				} else if ($status == 'FINISHED') {
					$message .= "(4/$numStages) " . Mage::helper('recsins')->__("Purchases uploaded OK") . "<br>";
				} else if ($status == 'ERROR') {
					$message .= Mage::helper('recsins')->__("Error occurred while uploading Purchases") . "<br>";
					$message .= Mage::helper('recsins')->__("Catalog upload aborted") . "<br>";
				}
				return $message;
			} else {
				$message .= "(4/$numStages) " . Mage::helper('recsins')->__("Purchases uploaded OK") . "<br>";
			}
		}

		return $message;
	}

	protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
		$response = $this->getResponse();
		$response->setHeader('HTTP/1.1 200 OK', '');
		$response->setHeader('Pragma', 'public', true);
		$response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
		$response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
		$response->setHeader('Last-Modified', date('r'));
		$response->setHeader('Accept-Ranges', 'bytes');
		$response->setHeader('Content-Length', strlen($content));
		$response->setHeader('Content-type', $contentType);
		$response->setBody($content);
		$response->sendResponse();
		die;
	}

}