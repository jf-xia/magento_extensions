<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * Test code for observing system events
 * 
 */
class FourTell_Recommend_Model_Observer
{
	function uploadData($observer) {
    	// If extension is not enabled then return
		if (Mage::getStoreConfig('recommend/config/enabled') != "1")
			return;
		
		require_once("FourTell/Recommend/controllers/Adminhtml/UploadformController.php");
		
		$uploader = new FourTell_Recommend_Adminhtml_UploadformController();
		$uploader->generateAllData(1);
	}
	
	
	function getMode() {
		if (Mage::getStoreConfig('recommend/config/mode') == "Test") {
			return "biz";
		}

		return "net";
	}
		
	
	function getClientId() {
		return Mage::getStoreConfig('recommend/config/client_id');
	}


	function recommendTypeStringToId($type) {
		switch (strtolower($type)) {
			case 'cross sell':
				return 0;
			break;
			
			case 'related':
				return 1;
			break;
			
			case 'upsell':
				return 2;
			break;
			 
			case 'similar':
				return 3;
			break;

			case 'top sellers':
				return 4;
			break;
		}
		
		return 1;
	}


	function getRecommendations($resultType, $productIds, $cartIds, $customerId, $numResults) {
		$service_url = 	'http://www.4-tell.' . $this->getMode() 						. 
						'/Boost2.0/rest/GetRecIDs/string'								. 
						'?clientAlias='	. $this->getClientId() 							. 
						'&productIds='	. $productIds									.
						'&cartIds='		. $cartIds										.
						'&customerId='	. $customerId									.
						'&numResults='	. $numResults									.
						'&resultType='	. $this->recommendTypeStringToId($resultType)	.
						'&format=CommaDelimited';

		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		curl_close($curl);

		$IDs = str_ireplace('<string xmlns="http://schemas.microsoft.com/2003/10/Serialization/">', '', $curl_response);
		$IDs = str_ireplace('</string>', '', $IDs);
		$IDs = str_ireplace(' ', '', $IDs);

		return explode(",", $IDs);
	}


	public function salesOrderPlaceAfter($observer) {
		$order = $observer->getEvent()->getOrder();
		
		$ret = $this->sendOrder($order);
		
		return $ret;
	}
	
	function sendOrder($order) {
		$mode = $this->getMode();
		$clientId = $this->getClientId();
		$customerID = $order->getCustomerId();
		
		$items = $order->getItemsCollection();
		
		foreach($items as $item) {
			$service_url = 	"http://www.4-tell." 					. $mode							. 
							"/Boost2.0/sale/UploadData/singleSale"	.
							"?clientAlias="							. $clientId 					. 
							"&customerID="							. $customerID					. 
							"&productID="							. $item->getData("product_id")	. 
							"&quantity="							. $item->getQtyToShip()			;
			$curl = curl_init($service_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
			$curl_response = curl_exec($curl);
			curl_close($curl);
		}
	}
}
