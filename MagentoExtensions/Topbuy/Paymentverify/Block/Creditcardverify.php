<?php

class Topbuy_Paymentverify_Block_Creditcardverify extends Mage_Core_Block_Template
{
	//defind all private members
	private $_moneyback = 0; 
	private $_uuid = '';
	private $_orderid = 0;
	private $_verifyflag = 0;
	private $_order; //Mage::getModel("sales/order") obj
	private $_verifyNumber1 = 0;
	private $_verifyNumber2 = 0;
	private $_verifyNumberInt = 0; 
	 
	//encap all private members into public functions
	public function getMoneyBack()
	{
		return $this->_moneyback;
		}
	public function setMoneyBack($moneyback)
	{
		$this->_moneyback = $moneyback;
		}
	public function getUuid()
	{
		return $this->_uuid;
		}
	public function setUuid($uuid)
	{
		$this->_uuid = $uuid;
		}
	public function getOrderId()
	{
		return $this->_orderid;
		}
	public function setOrderId($orderid)
	{
		$this->_orderid = $orderid;
		}	
	public function getOrder()
	{
		return $this->_order;
		}
	public function setOrder($orderid)
	{	
		$this->_order = Mage::getModel("sales/order")->load($orderid);
		}		
	public function getVerifyFlag()
	{
		return $this->_verifyflag;
		}
	public function setVerifyFlag($verifyflag)
	{
		$this->_verifyflag = $verifyflag;
		}		
	public function getVerifyNumber1()
	{
		return $this->_verifyNumber1;
		}
	public function setVerifyNumber1($verifyNumber1)
	{
		$this->_verifyNumber1 = $verifyNumber1;
		}
	public function getVerifyNumber2()
	{
		return $this->_verifyNumber2;
		}
	public function setVerifyNumber2($verifyNumber2)
	{
		$this->_verifyNumber2 = $verifyNumber2;
		}
	public function getVerifyNumberInt()
	{
		return $this->_verifyNumberInt;
		}
	public function setVerifyNumberInt($verifyNumberInt)
	{
		$this->_verifyNumberInt = $verifyNumberInt;
		}

	//start all functions
	
	public function getVerifyDetail()
	{  
		//return order payment verify status 
		/**
		0: invalid UUID
		1: valid to validate
		2: successfully validated
		3: failed validated
		**/	
		//get uuid from url param "uuid"
		if ( $this->getUuid() == '')
		{
			return 0;
			}
	 
		//query payment trans based on uuid	 	
		$payments = Mage::getResourceModel('sales/order_payment_collection')->addFieldToFilter('money_back_uuid', $this->getUuid());
		 
		//parse if there is a hit record
		foreach ($payments as $_payment){			
			if($_payment['parent_id'] != "")
			{
				$this->setOrderId($_payment['parent_id']);
				$this->setMoneyBack($_payment['money_back']);
				$this->setVerifyFlag($_payment['verify_flag']);
				$this->setOrder($this->getOrderId());
				
				}
			
			//var_dump($_payment);
		}  
		//if order was verified before, then return verifed flag directly
		if ($this->getVerifyFlag() == 2 || $this->getVerifyFlag() == 3)
		{
			return $this->getVerifyFlag();
			}
		
		//return true if found record
		elseif ($this->getOrderId() > 0 && $this->getMoneyBack() > 0)
		{
			//it is valid to verify order
			if($this->initVerifyNumber())
			{
				return 1;
				}
			else
			{
				return 0;
				} 
		} 
		else
		{
			return 0;
		}
	}
	
	public function initVerifyNumber()
	{
		$tem_moneyback = $this->getMoneyBack();  
		$tem_moneyback_len = strlen(trim($tem_moneyback));
		//echo 'mb'.$tem_moneyback.'xxx'. $tem_moneyback_len.'xxx';
		//echo substr($tem_moneyback,0,$tem_moneyback_len-2).'xxx';
		
		if ($tem_moneyback_len < 2)
		{
			return false;
			}
		else
		{
			$this->setVerifyNumber1(substr($tem_moneyback, -1));
			$this->setVerifyNumber2(substr($tem_moneyback, -2,1)); 
			$this->setVerifyNumberInt(substr($tem_moneyback,0,$tem_moneyback_len-2));
			return true;
			}
	}
	
	public function doVerify($_postData)
	{
		//process verification
		 //it is a post request. update record first
		 $i_uuid = $_postData['i_uuid']; 
		 if (isset($_postData['i_numberint']))
		 {
			 $i_numberint = $_postData['i_numberint'];
		 }
		 else
		 {
			 $i_numberint = '';
			 } 
		 if (isset($_postData['i_number1']))
		 {
			 $i_number1 = $_postData['i_number1'];
		 }
		 else
		 {
			 $i_number1 = '';
		 }
		 if (isset($_postData['i_number2']))
		 {
			 $i_number2 = $_postData['i_number2'];
		 }
		 else
		 {
			 $i_number2 = '';
		 }
		 if ($i_uuid != "" && $i_number1 != "" && $i_number2 != "" && $i_numberint != "")
		 {
			 //passed all validdate, now it is time to update
			 $money_back_response = $i_numberint.$i_number1.$i_number2;
			 $payments = Mage::getResourceModel('sales/order_payment_collection')->addFieldToFilter('money_back_uuid', $i_uuid);
			 foreach ($payments as $_payment)
			 {			
				 if($_payment['parent_id'] != "")
				 {
					$real_money_back = $_payment['money_back'];
					$_payment->setMoneyBackResponse($money_back_response);
					if ($real_money_back == $money_back_response)
					{
						//success 
						$_payment->setVerifyFlag(2);
						
						}
					else
					{
						//failed
						$_payment->setVerifyFlag(3);
						}
					$_payment->save();
				 }
			 }
		 }
		
		}
	 
	  public function checkUUID()
	  {
		 $postData =  $this->getData('postData') ;
		 //var_dump($postData);
		 
		 if (isset($postData['i_uuid']))
		 {
			$this->doVerify($postData);			
		 } 
		 
		 $this->setUuid($this->getData('uuid'));	
		 // var_dump($this->getUuid());
		 return $this->getVerifyDetail();
		  
	  }
}