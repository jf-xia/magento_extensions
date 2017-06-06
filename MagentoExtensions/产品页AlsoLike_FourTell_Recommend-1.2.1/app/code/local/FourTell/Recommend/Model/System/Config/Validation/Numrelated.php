<?php
	class FourTell_Recommend_Model_System_Config_Validation_Numrelated extends Mage_Core_Model_Config_Data
	{
	    public function save()
	    {
	        $numrelated = $this->getValue(); // get the value from our config
	        
	        if (strlen($numrelated) && is_numeric($numrelated) === false)
	        {
	            Mage::throwException("Please enter a valid number of Related recommendations.");
	        }
	 		
	        return parent::save();  // call original save method so whatever happened
	                                // before still happens (the value saves)
	    } 
	}
?>
