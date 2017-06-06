<?php

class NeedTool_ChinaYto_Model_Carrier_Cnexpcod extends Mage_Shipping_Model_Carrier_Abstract
{
    protected $_code  = 'ChinaYto_cnexpcod';
    protected $_result;
    
  public function collectRates(Mage_Shipping_Model_Rate_Request $request)
  {
		if (!$this->getConfigData('active')) {
			return false;
		}

		$dest_country_id =  $request->_data['dest_country_id'];
		Mage::log($dest_country_id);
		if (!($dest_country_id == 'CN')) {
			return false;
		}
		// check if in beijing;
//		$dest_region_code =  $request->_data['dest_region_code'];
//		if ($dest_region_code == 'Beijing') {
//			return false;
//		}

		$fee = 0.00;
		
		$package_value_with_discount = $request->_data['package_value_with_discount']; //base currency
		$freefrom = doubleval($this->getConfigData('freefrom')); // base currency
		
		if ( $freefrom >0 && $package_value_with_discount <= $freefrom ) {
			$fee = $package_value_with_discount * 0.15; // base currency
			$minimalfee = doubleval($this->getConfigData('minimalfee')); // base currency
			if( $fee < $minimalfee ){
				$fee = $minimalfee; 
			}
		}
		$result = Mage::getModel('shipping/rate_result');
		$method = Mage::getModel('shipping/rate_result_method');

		$method->setCarrier($this->_code);
		$method->setCarrierTitle($this->getConfigData('title'));

		//$method->setMethod($this->_code);
		//$method->setMethodTitle($this->getConfigData('title'));
		//$method->setMethodDescription(isset($params['description']) ? $this->getMethodDescription($request,$params) : '');
		$method->setPrice($fee);
		$method->setCost($fee);

		$result->append($method);
		
		Mage::log($result);
		
		return $result;
  }
  
  public function isTrackingAvailable()
	{
		return true;
	}
	
	public function getTrackingInfo($tracking_number)
	{
		$tracking_result = $this->getTracking($tracking_number);

		if ($tracking_result instanceof Mage_Shipping_Model_Tracking_Result)
		{
			if ($trackings = $tracking_result->getAllTrackings())
			{
				return $trackings[0];
			}
		}
		elseif (is_string($tracking_result) && !empty($tracking_result))
		{
			return $tracking_result;
		}
		
		return false;
	}

	protected function getTracking($tracking_number)
	{
		//$tracking_url = $this->getConfigData('tracking_view_url');
		$tracking_url = $this->getConfigData('tracking_url');
		$parts = explode(':',$tracking_number);
		if (count($parts)>=2)
		{
			$tracking_number = $parts[1];
			$method_config = $this->getMethodConfigByCode($parts[0]);
			if (isset($method_config['tracking_url']))
			{
				$tracking_url = $method_config['tracking_url'];
			}
		}
		$config = $this->getConfig();

		$tracking_result = Mage::getModel('shipping/tracking_result');

		$tracking_status = Mage::getModel('shipping/tracking_result_status');
		$tracking_status->setCarrier($this->_code);
		$tracking_status->setCarrierTitle($this->getConfigData('title'));
		$tracking_status->setTracking($tracking_number);
		$tracking_status->addData(
			array(
				'status'=>'<a target="_blank" href="'.str_replace('{tracking_number}',$tracking_number,$tracking_url).'">'.__('track the package').'</a>'
			)
		);
		$tracking_result->append($tracking_status);

		return $tracking_result;
	}


}