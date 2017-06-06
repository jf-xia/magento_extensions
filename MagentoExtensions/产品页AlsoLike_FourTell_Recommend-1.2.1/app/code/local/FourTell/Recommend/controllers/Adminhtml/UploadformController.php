<?php
function compare_type($a, $b) {
	return strnatcmp($a['name'], $b['name']);
}

/**
 *
 * 4-Tell Product Recommendations
 *
 * This is for processing the form in the admin
 * Catalog/4-Tell Recommendations
 *
 */
class FourTell_Recommend_Adminhtml_UploadformController extends Mage_Adminhtml_Controller_Action {
	/**
	 * Show debugging export details
	 *
	 * @var booelan
	 */
	public $showUploadDetails = false;

	/**
	 * Global variable for generating file contents
	 *
	 * @var string
	 */
	public $fileContents = "";

	/**
	 * Exclude product options
	 *
	 * @var array
	 */
	public $excludeOptions = array();

	/**
	 * Options for uploading data (recommend/etc/upload.xml)
	 *
	 * @var class
	 */
	public $uploadSettings = null;

	/**
	 * An array for holding all the months to upload sales for
	 *
	 * @var array
	 */
	public $salesFiles = null;

	/**
	 * Maximum number of orders to upload at a time
	 *
	 * @var integer
	 */
	public $maxOrders = 500;

	/**
	 * An array for holding url parameters
	 *
	 * @var array
	 */
	public $salesAppend = array();

	/**
	 * An array for holding all the names of sales files to upload
	 *
	 * @var string
	 */
	public $options = null;

	/**
	 * An array for holding all the names of sales files to upload
	 *
	 * @var string
	 */
	public $salesFileNames = "";

	public $isAjax = false;

	/**
	 *
	 * Handler for clicking Catalog/4-Tell Recommendations
	 *
	 */
	public function indexAction() {
		$this->processPostAction();

		$this->loadLayout()->renderLayout();

		//Mage::log(Mage::app()->getConfig(), null, "4tell.log");
		//Mage::log(Mage::getBaseDir(), null, "4tell.log");
		//Mage::log(Mage::getBaseUrl(), null, "4tell.log");
		//Mage::log(Mage::getRoot(), null, "4tell.log");
	}

	/**
	 *
	 * Handler for submit button on admin form
	 *
	 */
	public function postAction() {
		$this->processPostAction();

		$this->_redirect('*/*');
	}

	/**
	 *
	 * Handler for submit button on admin form
	 *
	 */
	public function processPostAction() {
		try {
			//$this->order_collection = Mage::getResourceModel('sales/order_collection');

			//$this->getProductAttributes();

			set_time_limit(3000);

			$this->options = Mage::getSingleton('FourTell_Recommend_Model_Upload');
			$this->options->load();

			$this->getUploadSettings();
			$this->buildSalesFileNames();
					
			$this->processGetAction();

			$post = $this->getRequest()->getPost();

			if (!empty($post)) {
				$what_data = $post['what_data'];

				$this->options = Mage::getSingleton('FourTell_Recommend_Model_Upload');
				if (!is_object($this->options)) {
					throw new Exception("Unable to get singleton: FourTell_Recommend_Model_Upload");
				}
				$this->options->save($post);
				$message = $this->__('Settings have been saved successfully');
				Mage::getSingleton('core/session')->addSuccess($message);

				$this->options = Mage::getSingleton('FourTell_Recommend_Model_Upload');
				$this->options->load();
				if (!is_object($this->options)) {
					throw new Exception("Unable to get singleton: FourTell_Recommend_Model_Upload");
				}

				if ($what_data == "upload_data") {
					if (!$this->getEnabled()) {
						return false;
					}

					if (strtolower($this->options->getSetting('upload_sales_data')) == "all") {
						$this->generateAllData(Mage::getStoreConfig('recommend/config/maxsalesdataageinmonths'));
					} else {
						$this->generateAllData(1);
					}
				}
			}
		} catch (Exception $e) {
			$this->processException($e, "processPostAction");
		}
	}

	/**
	 *
	 * Parse the url parameters from redirections
	 *
	 * If it is a redirecton then get values and upload next sales page orders
	 *
	 * If not a redirection then set default values.
	 *
	 */
	public function processGetAction() {
		$params = $this->getRequest()->getParams();

		if (array_key_exists('key', $params)) {
			$this->salesAppend['key'] = $params['key'];		
		}

		if (array_key_exists('isajax', $params)) {
			$this->isAjax = true;		
		}

		if (isset($params['cmd']) && isset($params['ym']) && isset($params['p'])) {
			$this->salesAppend['cmd'] = $params['cmd'];
			$this->salesAppend['ym'] = $params['ym'];
			$this->salesAppend['p'] = intval($params['p']);

			$this->showUploadProgress($this->salesAppend['ym'], $this->salesAppend['p']);

			if ($this->salesAppend['cmd'] == "usf") {
				$this->uploadYearMonthOrders($this->salesAppend['ym'], $this->salesAppend['p']);
			}
			
		} else if (isset($params['cmd']) && $params['cmd'] == "ucb") {
			$this->exportConfiguration();
			
		} else if (isset($params['cmd']) && $params['cmd'] == "ucd") {
			$this->exportCategoryData();
			
		} else if (isset($params['cmd']) && $params['cmd'] == "urd") {
			$this->exportReplacementData();
			
		} else if (isset($params['cmd']) && $params['cmd'] == "ued") {
			$this->exportExclusionData();
			
		} else if (isset($params['cmd']) && $params['cmd'] == "upd") {
			$this->exportProductData();
			
		} else if (isset($params['cmd']) && $params['cmd'] == "gen") {
			$this->startGenerator();
			
		} else {
			$this->salesAppend['cmd'] = "usf";
			$this->salesAppend['ym'] = $this->getNextUploadDate();
			$this->salesAppend['p'] = 0;
		}
	}

	/**
	 *
	 * Display a list of sales files that have been uploaded
	 *
	 */
	function showUploadProgress($sf, $p) {
		try {
			if (strtolower($this->options->getSetting('upload_sales_data')) != "none") {
				$keys = array_keys($this->salesFiles);
				$cf = $this->salesFiles[$sf];
	

				Mage::getSingleton('core/session')->addWarning("Uploading sales orders, please wait...");

				/*
				if ($cf['last'] == 1 && $p + 1 == $cf['pages']) {
					Mage::getSingleton('core/session')->addWarning("Upload of sales orders complete.");

					$this->startGenerator();
				} else {
					//Mage::getSingleton('core/session')->addWarning("Uploading sales orders, please wait... <img src='/skin/adminhtml/default/default/images/rule-ajax-loader.gif' />");
					Mage::getSingleton('core/session')->addWarning("Uploading sales orders, please wait...");
				}
				*/
				
				foreach ($keys as $idx => $key) {
					if ($sf === $key) {
						return;
					}
					
					Mage::getSingleton('core/session')->addWarning("Uploaded data for: sales_" . $key . ".txt");
				}
			}
		} catch (Exception $e) {
			$this->processException($e, "showUploadProgress");
		}
	}

	/**
	 *
	 * Display a list of sales files that have been uploaded
	 *
	 */
	function showUploadComplete() {
		try {
			Mage::getSingleton('core/session')->addWarning("Upload of sales orders complete.");
			$this->startGenerator();
		} catch (Exception $e) {
			$this->processException($e, "showUploadComplete");
		}
	}

	/**
	 *
	 * Get upload settings through the upload class
	 *
	 */
	public function getUploadSettings() {
		try {
			$this->uploadSettings = Mage::getSingleton('FourTell_Recommend_Model_Upload');
			if (!is_object($this->uploadSettings)) {
				throw new Exception("Unable to get singleton: FourTell_Recommend_Model_Upload");
			}

			$this->uploadSettings->load();
			//$this->uploadSettings->setProductAttributes($this->getProductAttributes());
		} catch (Exception $e) {
			$this->processException($e, "getUploadSettings");
		}
	}

	/**
	 *
	 * Shortcut for getting admin setting of client id
	 *
	 */
	function getClientId() {
		try {
			return Mage::getStoreConfig('recommend/config/client_id');
		} catch (Exception $e) {
			$this->processException($e, "getClientId");
		}
	}

	/**
	 *
	 * Shortcut for getting admin setting of enabled
	 *
	 */
	function getEnabled() {
		try {
			return intval(Mage::getStoreConfig('recommend/config/enabled'));
		} catch (Exception $e) {
			$this->processException($e, "getEnabled");
		}
	}

	/**
	 *
	 * Generate / upload data
	 *
	 */
	function generateAllData($howMany) {
		try {
			$ups = $this->uploadSettings->getSetting('upload_sales_data');
			$upr = $this->uploadSettings->getSetting('upload_replacement_data');
			$upe = $this->uploadSettings->getSetting('upload_exclusion_data');
			$upp = $this->uploadSettings->getSetting('upload_product_data');

			$this->exportConfiguration();

			$this->exportCategoryData();

			if (strtolower($upr) != "none")
				$this->exportReplacementData();

			if ($upe == "Yes")
				$this->exportExclusionData();

			if ($upp == "Yes")
				$this->exportProductData();

			if (strtolower($ups) != "none") {
				$this->exportSalesData($howMany);
			} else {
				$this->startGenerator();
			}
		} catch (Exception $e) {
			$this->processException($e, "generateAllData");
		}
	}

	/**
	 *
	 * Generate and upload config settings
	 *
	 */
	function exportConfiguration() {
		$generate = "false";
		
		try {
			$today = date("m-d-Y");

			if (strtolower($this->uploadSettings->getSetting('upload_exclusion_data')) == "yes")
				$upload_exclusion = 1;
			else
				$upload_exclusion = 0;

			if (strtolower($this->uploadSettings->getSetting('upload_replacement_data')) != "none")
				$upload_replacement = 1;
			else
				$upload_replacement = 0;

			$contents = $this->getClientId() . "\tConfigBoost.txt\t" . "false" . "\r\n" . 
						"Version\t"					. "3" 																. "\r\n" .
						"Owner\t" 					. Mage::getStoreConfig('recommend/config/owner') 					. "\r\n" .
						"Email\t" 					. Mage::getStoreConfig('recommend/config/email') 					. "\r\n" . 
						"ReportLevel\t"				. Mage::getStoreConfig('recommend/config/reportlevel') 				. "\r\n" .
						"ExclusionsExist\t"			. $upload_exclusion 												. "\r\n" .
						"ReplacementsExist\t"		. $upload_replacement 												. "\r\n" .
						"MaxSalesDataAgeInMonths\t" . Mage::getStoreConfig('recommend/config/maxsalesdataageinmonths') 	. "\r\n" ;
			
			// Upload ConfigBoost.txt contents to server
			$this->exportFile("ConfigBoost.txt", $contents, "create");
		} catch (Exception $e) {
			$this->processException($e, "exportConfiguration");
		}
	}

	/**
	 *
	 * Generate and upload category data
	 *
	 */
	function exportCategoryData() {
		$generate = "false";
		
		try {
			// Create file header
			$today = date("m-d-Y");
			$this->fileContents = $this->getClientId() . "\tAttribute1Names.txt\t" . $generate . "\r\nVersion\t2\t" . $today . "\r\nAttID\tName\r\n";

			// Get all categories
			$category = Mage::getModel('catalog/category');
			if (!is_object($category)) {
				throw new Exception("Unable to get model: catalog/category");
			}

			$collection = $category->getCollection();
			if (!is_object($collection)) {
				throw new Exception("Unable to get category collection");
			}

			$collection->addAttributeToSelect('*');
			$collection->addFieldToFilter('name', array('like' => '%'));

			// Loop through all and add to file
			foreach ($collection as $item) {
				$this->fileContents = $this->fileContents . $item->getData('entity_id') . "\t" . $item->getData('name') . "\r\n";
			}

			// Upload Attribute1Names.txt contents to server
			$this->exportFile("Attribute1Names.txt", $this->fileContents, "create");
		} catch (Exception $e) {
			$this->processException($e, "exportCategoryData");
		}
	}

	/**
	 *
	 * Generate sales file for the number of months specified
	 * Each file is uploaded as a separate file
	 *
	 */
	function exportSalesData($howMany) {
		try {
			$newdate = date('Y-m-j');

			// Loop for the number of months specified
			// Either 1 or the number from admin settings

			//for ($cd=0; $cd<$howMany; $cd++) {
			// Generate next date string for filename
			$dp = explode('-', $newdate);
			$newdate = strtotime('-1 month', strtotime($newdate));
			$newdate = date('Y-m-j', $newdate);

			$fm = $dp[1];
			$fy = $dp[0];

			$this->uploadYearMonthOrders($fy . "-" . $fm, 0);
			//}
		} catch (Exception $e) {
			$this->processException($e, "exportSalesData");
		}
	}

	/**
	 *
	 * Generate sale data for a specified month and export it
	 *
	 */
	function uploadYearMonthOrders($sf, $p) {
		try {
			$generate = false;
			
			$fn = "sales_" . $sf . ".txt";
			$sp = explode("-", $sf);

			$cf = $this->salesFiles[$sf];
			$isLastUploadMonth = $cf['last'];

			if ($p == 0) {
				$type = "create";
			} else {
				$type = "append";
			}

			$items = null;
			$item = null;
			$data = null;
			$configurable_product_model = Mage::getModel('catalog/product_type_configurable');

			// Get the sales data for the month as a collection
			$collection = $this->getSalesOrdersForMonth($sp[0], $sp[1], $p);

			if ($collection->count() < $this->maxOrders && $isLastUploadMonth == 1) {
				$completed = "uploadComplete";
			} else if ($collection->count() < $this->maxOrders) {
				$completed = "monthComplete";
			} else {
				$completed = "false";
			}

			$today = date("m-d-Y");
			unset($contents);

			if ($type == "create") {
				$contents = $this->getClientId() . "\t" . $fn . "\t" . $type . "\t" . $generate . "\r\nVersion\t2\t" . $today . "\r\nProduct ID\tCustomer ID\tQuantity\tDate\r\n";
			} else {
				$contents = $this->getClientId() . "\t" . $fn . "\t" . $type . "\t" . $generate . "\r\n";
			}

			// Loop through the collection data
			foreach ($collection as $order) {
				// Get customer ID of use who placed the order
				// if the customer was an account
				$customerId = $order->getData('customer_id');
				if ($customerId == '') {
					// Get email of use who placed the order if
					// the order was placed with guest checkout
					$customerId = $order->getData('customer_email');
					if ($customerId == '') {
						// Get order ID as last resort to use
						// as the customer ID
						$customerId = $order->getData('increment_id');
					} else {
						// If we ended up with an email address then
						// make into a hash so we are not transmitting
						// or storing readable personal information
						$customerId = md5($customerId);
					}
				}

				// Get all the order items for the current order
				$items = $order->getAllItems();

				// Loop through the order items
				foreach ($items as $item) {
					// Get the data for the current item
					$product_id = $item->getData('product_id');
					$product_type = $item->getData('product_type');
					$created_at = $item->getData('created_at');
					$qty_ordered = $item->getData('qty_ordered');
					$created_at = $order->getData('created_at');

					// If the item in not configurable then proceed
					if ($product_type != "configurable") {
						$dt = new DateTime($created_at);

						if ($product_type == "simple" || $product_type == "") {
							//$configurable_product_model = Mage::getModel('catalog/product_type_configurable');
							if (!is_object($configurable_product_model)) {
								throw new Exception("Unable to get model: catalog/product_type_configurable");
							}

							$parentIdArray = $configurable_product_model->getParentIdsByChild($product_id);
							if (isset($parentIdArray[0])) {
								$product_id = $parentIdArray[0];
							}
						}

						$contents = $contents . $product_id . "\t" . $customerId . "\t" . intval($qty_ordered) . "\t" . $dt->format('Y-m-d') . "\r\n";
					}

					unset($item);
					unset($data);
				}
			}

			unset($collection);

			$this->exportFile($fn, $contents, $type);


			if ($this->isAjax) {
				$msg = array(
					'status' => $completed,
					'month' => "",
					'page' => ""
				);
				
				if ($completed != "uploadComplete") {
					if ($completed == "monthComplete") {
						$month = $this->getNextUploadDate($sf);
						$page = 0;
						$msg['month'] = $month;
						$msg['page'] = $page;
					} else {
						$page = $p+1;
						$msg['month'] = $sf;
						$msg['page'] = $page;
					}
				}

				/*
	            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
	            $this->setFlag('', self::FLAG_NO_POST_DISPATCH, true);
	            if ($this->getRequest()->getQuery('isAjax', false) || $this->getRequest()->getQuery('ajax', false)) {
	                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array(
	                    'error' => true,
	                    'message' => $_keyErrorMsg
	                )));
	            }
				*/
				
				/*
				Mage::log("uploadYearMonthOrders", null, "4tell.log");
				Mage::log(Zend_Json, null, "4tell.log");
				Mage::log(Zend_Json::encode($msg), null, "4tell.log");
				Mage::log($this->getResponse(), null, "4tell.log");
				Mage::log($this->getResponse()->setBody(Zend_Json::encode($msg)), null, "4tell.log");
				*/
				
				$this->getResponse()->setBody(Zend_Json::encode($msg));

				//$this->getResponse()->outputBody();
				
				/*
				header('Cache-Control: no-cache, must-revalidate');
				header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
				header('Content-type: application/json');
				echo json_encode($msg);
				exit(json_encode($msg));
				*/
				
				//header('X-JSON: ('.json_encode($msg).')');
				//die();
			}
			
			
			if ($completed != "uploadComplete") {
				$this->processNextSales($sf, $p, $completed);
				
			} else {
				$this->showUploadComplete();
			}
		} catch (Exception $e) {
			$this->processException($e, "uploadYearMonthOrders");
		}
	}

	/**
	 *
	 * After uploading a sales files this gets called, this determines if we
	 * should go to the next page of ther current sales file or go to the next
	 * sales file or if we have finished uploading all sales data
	 *
	 */
	function processNextSales($sf, $p, $completed) {
		$cf = $this->salesFiles[$sf];
		$isLastUploadMonth = $cf['last'];

		if ($completed == "monthComplete") {
			$this->salesAppend['ym'] = $this->getNextUploadDate($sf);
			$this->salesAppend['p'] = 0;
		} else {
			$this->salesAppend['p']++;
		}
		
		/*
		$newUrl = 	"http://" . ($_SERVER['REMOTE_PORT']==80?"s":"").
		$_SERVER['SERVER_NAME'] 									.
		"/index.php/" 	. $this->getRequest()->getModuleName() 		.
		"/"			. $this->getRequest()->getControllerName() 	.
		"/"			. $this->getRequest()->getActionName() 		;
		*/
		 
		 $newUrl = 	Mage::getBaseUrl()							.
		 			$this->getRequest()->getModuleName() 		.
		 "/"	. 	$this->getRequest()->getControllerName() 	.
		 "/index"												;
		 //"/"	. 	$this->getRequest()->getActionName() 		;
		 
		 if (array_key_exists('key', $this->salesAppend) && !empty($this->salesAppend['key'])) {
		 	$newUrl .= "/key/" . $this->salesAppend['key'];
		 }
		 
		 $newUrl .= 
		 "/cmd/"		. $this->salesAppend['cmd']				.
		 "/ym/"			. $this->salesAppend['ym']				.
		 "/p/"			. $this->salesAppend['p']				;

		//Mage_Adminhtml_Controller_Action::app()->getFrontController()->getResponse()->setRedirectUrl($newUrl);
		
		//$this->_redirectUrl($newUrl);

		//$this->options->setRedirectUrl($newUrl);
		
		//$jsUrl = '<script type="text/javascript">window.location.replace("'.$newUrl.'");</script>';
		$jsUrl = '<script type="text/javascript">window.location.href="'.$newUrl.'";</script>';
		Mage::getSingleton('core/session')->addWarning($jsUrl);

		//header("refresh:0;url=" . $newUrl);

		//header("Location: " . $newUrl);
	}

	/**
	 *
	 * Check to see of the current sales file is the last sales file in the
	 * the array of sales file to upload
	 *
	 */
	function checkIsLastUploadMonth($sf) {
		$ret = 0;
		$keys = array_keys($this->salesFiles);

		foreach ($keys as $idx => $key) {
			if ($sf === $key && $idx == count($keys)) {
				$ret = 1;
			}
		}

		return $ret;
	}

	/**
	 *
	 * Get the next sales file from the array of sales files, if parameter
	 * is null then we return the first sales files, allowing null is used
	 * for when we do the first redirection sine we won't have a value at
	 * that pint to pass in
	 *
	 */
	function getNextUploadDate($sf = null) {
		$ret = 0;
		$keys = array_keys($this->salesFiles);

		$getNext = 0;
		if ($sf == null) {
			$getNext = 1;
		}

		foreach ($keys as $idx => $key) {
			if ($getNext == 1) {
				return $key;
			}

			if ($sf === $key) {
				$getNext = 1;
			}
		}

		return null;
	}

	/**
	 *
	 * Get the sales data for the specified month and year
	 *
	 */
	function getSalesOrdersForMonth($year, $month, $p) {
		$collection = null;

		try {
			$orders = Mage::getModel('sales/order');
			if (!is_object($orders)) {
				throw new Exception("Unable to get model: sales/order");
			}

			$collection = $orders->getCollection();
			if (!is_object($collection)) {
				throw new Exception("Unable to get order collection");
			}

			$collection->addFieldToFilter('created_at', array('gt' => $year . '-' . $month . '-01'));
			$collection->addFieldToFilter('created_at', array('lt' => $year . '-' . ($month + 1) . '-01'));
			$collection->setPageSize($this->maxOrders);
			$collection->setCurPage($p);
		} catch (Exception $e) {
			$this->processException($e, "exportSalesData");
		}

		$startPos = ($p * $this->maxOrders) + 1;
		Mage::log("Year: " . $year . "] Month: " . $month . "] Start: " . $startPos . "] Max: " . $this->maxOrders . "] Page: " . $p . "] Count: " . $collection->count());
		
		return $collection;
		//return $collection->setPage(($p * $this->maxOrders) + 1, $this->maxOrders);
	}

	/**
	 *
	 * Get the number of orders for the specified month and year
	 *
	 */
	function getSalesCountForMonth($year, $month) {
		try {
			$from = $year . "-" . intval($month) . "-1 00:00:00";
			$to = $year . "-" . ($month + 1) . "-1 00:00:00";

			$_collection = Mage::getResourceModel('sales/order_collection')->addAttributeToSelect('*')->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to));
			//->addExpressionAttributeToSelect('orders', 'COUNT(DISTINCT({{entity_id}}))', array('entity_id'));
		} catch (Exception $e) {
			$processException($e, "getSalesCountForMonth");
			return 0;
		}

		return $_collection->count();
	}

	/**
	 *
	 * Generate all the sales data filenames
	 * to be included in the configBoost.txt
	 *
	 */
	function buildSalesFileNames() {
		$outStr = "";

		try {
			if (strtolower($this->options->getSetting('upload_sales_data')) == "all") {
				$numMonths = Mage::getStoreConfig('recommend/config/maxsalesdataageinmonths');
			} else {
				$numMonths = 1;
			}

			$outStr = "";

			$newdate = date('Y-m-j');
			$dp = explode('-', $newdate);

			for ($cd = 0; $cd <= $numMonths + 1; $cd++) {
				$dp = explode('-', $newdate);

				$newdate = strtotime('-1 month', strtotime($newdate));
				$newdate = date('Y-m-j', $newdate);

				$fm = $dp[1];
				$fy = $dp[0];
				$fn = "sales_" . $fy . "-" . $fm . ".txt";

				$this->salesFiles[$fy . "-" . $fm] = array(
					'ym' => $fy . "-" . $fm, 
					'last' => ($cd == $numMonths) ? 1 : 0);

				$outStr .= $fn . "\r\n";
			}
		} catch (Exception $e) {
			$this->processException($e, "buildSalesFileNames");
		}

		$this->salesFileNames = $outStr;
		return $this->salesFileNames;
	}

	/**
	 *
	 * Export Product Data
	 *
	 */
	function exportProductData() {
		$generate = "false";
		
		try {
			// Create file header
			$today = date("m-d-Y");
			$this->fileContents = $this->getClientId() . "\tProductDetails.txt\t" . $generate . "\r\nVersion\t2\t" . $today . "\r\nProduct ID\tName\tAtt1 ID\tAtt2 ID\tPrice\tFilter\tLink\tImage Link\tStandard Code\r\n";

			// Get all products with just data needed
			$products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect(array('sku', 'name', 'price', 'image', 'url_path', 'type_id'), 'inner')
				->joinField('stock_status', 'cataloginventory/stock_status', 'stock_status', 'product_id=entity_id', array(
											'stock_status' => Mage_CatalogInventory_Model_Stock_Status::STATUS_IN_STOCK,
											//'website_id' => Mage::app()->getWebsite()->getWebsiteId(),
                        	));
			if (!is_object($products)) {
				throw new Exception("Unable to get model: catalog/product collection");
			}

			// Use an iterator to loop through the collection
			$iter = Mage::getSingleton('core/resource_iterator');
			if (!is_object($iter)) {
				throw new Exception("Unable to get singleton: core/resource_iterator");
			}

			$iter->walk($products->getSelect(), array( array($this, 'productDetailsCallback')), array('arg1' => '====['));

			// Upload ProductDetails.txt contents to server
			$this->exportFile("ProductDetails.txt", $this->fileContents, "create");
		} catch (Exception $e) {
			$this->processException($e, "exportProductData");
		}
	}

	/**
	 *
	 * This is the call back function used in the product iterator
	 *
	 */
	function productDetailsCallback($args) {
		try {
			$product = Mage::getModel('catalog/product');
			if (!is_object($product)) {
				throw new Exception("Unable to get model: catalog/product");
			}
			$product->setData($args['row']);

			$image = "";
			//$image = $product->getImageUrl();

			$cat = "";
			$cIds = $product->getAvailableInCategories();
			if (sizeof($cIds)) {
				$cat = $cIds[sizeof($cIds) - 1];
			}

			//$productUrl = "";
			$productUrl = $product->getProductUrl();

			// Check to see if the product should be included
			$incProduct = true;
			if ($product->type_id == "simple" || $product_type == "") {
				// Get product parent ids
				$configurable_product_model = Mage::getModel('catalog/product_type_configurable');
				if (!is_object($configurable_product_model)) {
					throw new Exception("Unable to get model: catalog/product_type_configurable");
				}
				$parentIdArray = $configurable_product_model->getParentIdsByChild($product->getEntityId());

				// Don't upload if it has parent ids
				if (isset($parentIdArray[0])) {
					//$product=Mage::getModel('catalog/product')->load($parentIdArray[0]);
					$incProduct = false;
				}
			}

			// Append product data to global variable: fileContents
			if ($incProduct) {
				if (!isset($parentIdArray[0])) {
					$this->fileContents = $this->fileContents . $product->getEntityId() . "\t" . $product->getName(). "[$product->type_id]" . "\t" . $cat . "\t" . "" . "\t" . str_replace(",", "", number_format($product->getPrice(), 2)) . "\t" . "" . "\t" . $productUrl . "\t" . $image . "\t" . $product->getSku() . "\r\n";
				}
			}
		} catch (Exception $e) {
			$this->processException($e, "productDetailsCallback");
		}
	}

	/**
	 *
	 * Export Product Replacement Data
	 *
	 */
	function exportReplacementData() {
		$generate = "false";
		
		try {
			// Create file header
			$today = date("m-d-Y");
			$this->fileContents = $this->getClientId() . "\tReplacements.txt\t" . $generate . "\r\nVersion\t2\t" . $today . "\r\nOld Product ID\tNew Product ID\r\n";

			if ($this->uploadSettings->getSetting('upload_replacement_data') == "Entire Catalog") {
				// Get all products with just data needed
				$products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect($this->uploadSettings->getSetting('replace_catalog_old_field'), $this->uploadSettings->getSetting('replace_catalog_new_field'))->addAttributeToSelect(array('name', 'price', 'image', 'url_path'), 'inner');

				// Use an iterator to loop through the collection
				Mage::getSingleton('core/resource_iterator')->walk($products->getSelect(), array( array($this, 'replacementCallback')), array('arg1' => '====['));

			} else if ($this->uploadSettings->getSetting('upload_replacement_data') == "Individual Items") {
				$opts = Mage::getSingleton('FourTell_Recommend_Model_Upload')->load();

				foreach ($opts['replace_options'] as $o) {
					$this->fileContents = $this->fileContents . (string)$o->oldid . "\t" . (string)$o->newid . "\r\n";
				}
			}

			// Upload Replacements.txt contents to server
			$this->exportFile("Replacements.txt", $this->fileContents, "create");
		} catch (Exception $e) {
			$this->processException($e, "exportReplacementData");
		}
	}

	/**
	 *
	 * This is the call back function used in the replacement iterator
	 *
	 */
	function replacementCallback($args) {
		try {
			$product = Mage::getModel('catalog/product');
			$product->setData($args['row']);

			$incProduct = true;
			if ($product->type_id == "simple" || $product_type == "") {
				// Get product parent ids
				$configurable_product_model = Mage::getModel('catalog/product_type_configurable');
				if (!is_object($configurable_product_model)) {
					throw new Exception("Unable to get model: catalog/product_type_configurable");
				}
				$parentIdArray = $configurable_product_model->getParentIdsByChild($product->getEntityId());

				// Don't upload if it has parent ids
				if (isset($parentIdArray[0])) {
					//$product=Mage::getModel('catalog/product')->load($parentIdArray[0]);
					//$incProduct = false;
					$dataProduct = Mage::getModel('catalog/product')->load($parentIdArray[0]);
				} else {
					$dataProduct = $product;
				}
			} else {
				$dataProduct = $product;
			}

			$this->fileContents = $this->fileContents . $product->getData($this->uploadSettings->getSetting('replace_catalog_old_field')) . "\t" . $dataProduct->getData($this->uploadSettings->getSetting('replace_catalog_new_field')) . "\r\n";
		} catch (Exception $e) {
			$this->processException($e, "replacementCallback");
		}
	}

	/**
	 *
	 * Export Product Exclusion Data
	 *
	 */
	function exportExclusionData() {
		$generate = "false";
		
		try {
			$opts = Mage::getSingleton('FourTell_Recommend_Model_Upload')->load();

			if (count($opts['exclude_options'])) {
				// Create file header
				$today = date("m-d-Y");
				$this->fileContents = $this->getClientId() . "\tDoNotRecommend.txt\t" . $generate . "\r\nVersion\t2\t" . $today . "\r\nProduct ID\r\n";

				// Create array of filter options
				$filters = array();
				foreach ($opts['exclude_options'] as $o) {
					$filt = array();
					$filt['attribute'] = (string)$o->field;
					$filt[(string)$o->compare] = (string)$o->value;

					$filters[] = $filt;
				}

				// Get product collection based on filter options
				$products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('sku')->addFieldToFilter($filters);

				// Use an iterator to loop through the collection
				Mage::getSingleton('core/resource_iterator')->walk($products->getSelect(), array( array($this, 'exclusionCallback')), array('arg1' => '====['));

				// Upload DoNotRecommend.txt contents to server
				$this->exportFile("DoNotRecommend.txt", $this->fileContents, "create");
			}
		} catch (Exception $e) {
			$this->processException($e, "exportExclusionData");
		}
	}

	/**
	 *
	 * This is the call back function used in the exclusion iterator
	 *
	 */
	function exclusionCallback($args) {
		try {
			$product = Mage::getModel('catalog/product');
			$product->setData($args['row']);

			$this->fileContents = $this->fileContents . $product->getEntityId() . "\r\n";
		} catch (Exception $e) {
			$this->processException($e, "exclusionCallback");
		}
	}

	/**
	 *
	 * Get mode (live or test)
	 *
	 */
	function getMode() {
		try {
			if (Mage::getStoreConfig('recommend/config/mode') == "Test") {
				return "biz";
			}

			return "net";
		} catch (Exception $e) {
			$this->processException($e, "getMode");
		}
	}

	/**
	 *
	 * Upload data to 4-Tell server
	 *
	 */
	function exportFile($fileName, $contents, $mode) {
		/*
		 */
		if (Mage::getStoreConfig('recommend/config/mode') == "Test" && $this->uploadSettings->getSetting('showUploadDetails') == "Yes") {
			try {
				if ($mode == "create") {
					$fmode = "w+";
				} else {
					$fmode = "a+";
				}

				$sf = fopen($fileName, $fmode);
				fwrite($sf, $contents, strlen($contents));
				fclose($sf);
			} catch (Exception $e) {
			}
		}

		try {
			$mode = $this->getMode();

			// The request URL prefix
			$request = "http://www.4-tell." . $mode . "/Boost2.0/rest/UploadData/stream";
			//Mage::log($request, null, "4tell.log");
			
			$curl = curl_init($request);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $contents);
			curl_setopt($curl, CURLOPT_VERBOSE, 0);
			curl_setopt($curl, CURLINFO_HEADER_OUT, 0);

			$response = curl_exec($curl);
			$info = curl_getinfo($curl);
			
			//Mage::log($info, null, "4tell.log");
			//Mage::log($response, null, "4tell.log");
			
			if ($response === false || $info['http_code'] != 200) {
				throw new Exception(curl_error($curl));
			}
			curl_close($curl);

			if (Mage::getStoreConfig('recommend/config/mode') == "Test" && $this->uploadSettings->getSetting('showUploadDetails') == "Yes") {
				Mage::getSingleton('core/session')->addWarning("Uploading data for: " . $fileName);
			}

		} catch (Exception $e) {
			$this->processException($e, "exportFile");
		}
	}

	/**
	 *
	 * Upload data to 4-Tell server
	 *
	 */
	function startGenerator() {
		try {
			$mode = $this->getMode();

			// The request URL prefix
			//$request = "http://www.4-tell." . $mode . "/Boost2.0/rest/GenerateDataTables?clientAlias=" .$this->getClientId(). "&reloadTables=true";
			$request = "http://www.4-tell." . $mode . "/Boost2.0/rest/GenerateDataTables?clientAlias=" .$this->getClientId(). "&reloadTables=true";

			$fields = array(
				'clientAlias'=>urlencode($this->getClientId()),
				'reloadTables'=>urlencode("true")
			);
			
			$fields_string = "";
			//url-ify the data for the POST
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string,'&');

			$session = curl_init($request);

			curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($session, CURLOPT_HEADER, 1);
			curl_setopt($session, CURLOPT_POST, 1);
			curl_setopt($session, CURLOPT_POSTFIELDS, $fields_string);

			curl_setopt($session, CURLOPT_VERBOSE, 0);
			curl_setopt($session, CURLINFO_HEADER_OUT, 0);

			$response = curl_exec($session);
			if ($response === false) {
				throw new Exception(curl_error($session));
			}
			curl_close($session);
			
			if (stripos($response, "200 OK") === false) {
				throw new Exception("Error generating data: " . ": " . $response);
				Mage::getSingleton('core/session')->addError("Generator reload failed");
				
				return false;
			} else {
				if (Mage::getStoreConfig('recommend/config/mode') == "Test" && $this->uploadSettings->getSetting('showUploadDetails') == "Yes") {
					Mage::getSingleton('core/session')->addWarning("Generator successful, data table reload queued");
				}
				
				return true;
			}
		} catch (Exception $e) {
			$this->processException($e, "exportFile");
			
			return false;
		}
	}

	/**
	 *
	 * Add error message to browser and log error file if logging is turned on
	 *
	 * @param e Exception object
	 * @param function Name of function where exception occurred
	 */
	function processException($e, $function) {
		Mage::getSingleton('core/session')->addError($e->getMessage());
		Mage::log("4-Tell (UploadformController::" . $function . ") :: " . $e->getMessage(), null, "4tell.log");
	}

	/**
	 *
	 * Test code for getting all available product attributes
	 *
	 * @return array a list of available product attributes
	 *
	 */
	public function getProductAttributes() {
		$prodAttrs = array();

		try {
			$attrs = Mage::getModel("Mage_Eav_Model_Entity_Attribute");
			if (!is_object($attrs)) {
				throw new Exception("Unable to get model: Mage_Eav_Model_Entity_Attribute");
			}

			$sets = Mage::getModel("Mage_Eav_Model_Entity_Attribute_Set");
			if (!is_object($sets)) {
				throw new Exception("Unable to get model: Mage_Eav_Model_Entity_Attribute_Set");
			}

			foreach ($attrs->getCollection() as $attr) {
				if ($attr->getData("entity_type_id") == 10)
					$prodAttrs[] = array('name' => $attr->getData("attribute_code"), 'label' => $attr->getData("frontend_label"));
			}

			usort($prodAttrs, 'compare_type');
		} catch (Exception $e) {
			$this->processException($e, "getProductAttributes");
		}

		return $prodAttrs;
	}
}
