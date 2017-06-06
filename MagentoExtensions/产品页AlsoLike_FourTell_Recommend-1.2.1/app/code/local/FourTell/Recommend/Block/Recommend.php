<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * This is the class that actually calls the 4-Tell service
 * and returns the products for recommendation
 * 
 */
class Recommender
{
	/**
	 * 
	 * Get the Client ID from the system configuration
	 * 
	 */
	function getClientId() {
		return Mage::getStoreConfig('recommend/config/client_id');
	}


	/**
	 * 
	 * Get mode (live or test)
	 * 
	 */
	function getMode() {
		if (Mage::getStoreConfig('recommend/config/mode') == "Test") {
			return "biz";
		}

		return "net";
	}
	
	
	/**
	 * 
	 * Convert type string to type id
	 * 
	 * @param type string name of the type
	 * 
	 * @return int id of the type
	 * 
	 */
	function getTypeId($type) {
		switch (strtolower($type)) {
			case "upsell":
				return 0;
			break;

			case "related":
				return 3;
			break;

			case "crosssell":
				return 0;
			break;

			case "topsellers":
				return 4;
			break;

			case "personalized":
				return 1;
			break;
		}
		
		return 3;
	}
	

	
	/**
	 * 
	 * Convert type string to type id
	 * 
	 * @param productIds
	 * @param cartIds
	 * @param customerId
	 * @param type
	 * @param numResults
	 * 
	 * @return array recommended products
	 * 
	 */
	function getRecommendations($productIds, $cartIds, $blockIds, $customerId, $type, $numResults) {
		try {
			$mode = $this->getMode();
			$clientId = $this->getClientId();
			$typeId = $this->getTypeId($type);
			
			$service_url = 	"http://www.4-tell." 				. $mode			. 
							"/Boost2.0/rest/GetRecIDs/string"	.
							"?clientAlias="						. $clientId 	. 
							"&productIDs="						. $productIds 	.
							"&cartIDs="							. $cartIds 		.
							"&blockIDs="						. $blockIds		.
							"&customerId="						. $customerId	.
							"&numResults="						. $numResults	.
							"&resultType=" 						. $typeId		.
							"&format=CommaDelimited";
	
			$curl = curl_init($service_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$curl_response = curl_exec($curl);
			$info = curl_getinfo($curl);
			curl_close($curl);
			
			if ($curl_response === false || $info['http_code'] != 200) {
				return array();
			}
	
			$IDs = str_ireplace('<string xmlns="http://schemas.microsoft.com/2003/10/Serialization/">', '', $curl_response);
			$IDs = str_ireplace('</string>', '', $IDs);
			$IDs = str_ireplace(' ', '', $IDs);
	
			return explode(",", $IDs);
		} catch (Exception $e) {
			return array();
		}
	}

}

