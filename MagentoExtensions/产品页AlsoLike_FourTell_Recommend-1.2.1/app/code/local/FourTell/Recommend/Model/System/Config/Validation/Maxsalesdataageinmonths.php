<?php
	class FourTell_Recommend_Model_System_Config_Validation_Maxsalesdataageinmonths extends Mage_Core_Model_Config_Data
	{
	    public function save()
	    {
	        $maxsalesdataageinmonths = $this->getValue(); // get the value from our config
	        
	        if (!is_numeric($maxsalesdataageinmonths) || $maxsalesdataageinmonths < 0 || $maxsalesdataageinmonths > 30)
	        {
	            Mage::throwException("Max Sales Data Age In Months must not be less than 1.");
	        }
	 
	        return parent::save();  // call original save method so whatever happened
	                                // before still happens (the value saves)
	    } 
	}
?>
