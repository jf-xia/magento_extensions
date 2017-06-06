<?php
	class FourTell_Recommend_Model_System_Config_Validation_Numupsell extends Mage_Core_Model_Config_Data
	{
	    public function save()
	    {
	        $numupsell = $this->getValue(); // get the value from our config
	        
	        if (strlen($numupsell) && is_numeric($numupsell) === false)
	        {
	            Mage::throwException("Please enter a valid number of Upsell recommendations.");
	        }
	 		
	        return parent::save();  // call original save method so whatever happened
	                                // before still happens (the value saves)
	    } 
	}
?>
