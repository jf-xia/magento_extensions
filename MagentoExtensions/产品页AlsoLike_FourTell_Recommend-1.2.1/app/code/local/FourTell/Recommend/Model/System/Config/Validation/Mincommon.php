<?php
	class FourTell_Recommend_Model_System_Config_Validation_Mincommon extends Mage_Core_Model_Config_Data
	{
	    public function save()
	    {
	        $mincommon = $this->getValue(); // get the value from our config
	        
	        if (!is_numeric($mincommon) || $mincommon < 1 || $mincommon > 10)
	        {
	            Mage::throwException("Min Common must be between 1 and 10.");
	        }
	 
	        return parent::save();  // call original save method so whatever happened
	                                // before still happens (the value saves)
	    } 
	}
?>
