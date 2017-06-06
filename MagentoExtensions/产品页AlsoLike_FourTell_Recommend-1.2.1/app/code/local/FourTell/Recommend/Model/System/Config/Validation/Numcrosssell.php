<?php
	class FourTell_Recommend_Model_System_Config_Validation_Numcrosssell extends Mage_Core_Model_Config_Data
	{
	    public function save()
	    {
	        $numcrosssell = $this->getValue(); // get the value from our config
	        
	        if (strlen($numcrosssell) && is_numeric($numcrosssell) === false)
	        {
	            Mage::throwException("Please enter a valid number of Cross Sell recommendations.");
	        }
	 		
	        return parent::save();  // call original save method so whatever happened
	                                // before still happens (the value saves)
	    } 
	}
?>
