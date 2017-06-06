<?php
	class FourTell_Recommend_Model_System_Config_Validation_Minlikelihood extends Mage_Core_Model_Config_Data
	{
	    public function save()
	    {
	        $minlikelihood = $this->getValue(); // get the value from our config
	        
	        if (!is_numeric($minlikelihood) || $minlikelihood < 1 || $minlikelihood > 30)
	        {
	            Mage::throwException("Min Likelihood must be between 1 and 30.");
	        }
	 
	        return parent::save();  // call original save method so whatever happened
	                                // before still happens (the value saves)
	    } 
	}
?>
