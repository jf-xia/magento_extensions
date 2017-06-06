<?php
	class FourTell_Recommend_Model_System_Config_Validation_Clientid extends Mage_Core_Model_Config_Data
	{
	    public function save()
	    {
	        $clientid = $this->getValue(); // get the value from our config
	        
	        if (trim($clientid) == "")   // exit if we're less than 10 digits long
	        {
	            Mage::throwException("You must enter your Client ID.");
	        }
	 
	        return parent::save();  // call original save method so whatever happened
	                                // before still happens (the value saves)
	    } 
	}
?>
