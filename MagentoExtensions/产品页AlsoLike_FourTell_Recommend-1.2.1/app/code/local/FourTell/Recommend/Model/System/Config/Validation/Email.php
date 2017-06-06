<?php
	class FourTell_Recommend_Model_System_Config_Validation_Email extends Mage_Core_Model_Config_Data
	{
	    public function save()
	    {
	        $email = $this->getValue(); // get the value from our config
	        
			if (preg_match( "/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/", $email))
			{
			} else {
				Mage::throwException("Please enter a valid email address.");
			}

	        return parent::save();  // call original save method so whatever happened
	                                // before still happens (the value saves)
	    } 
	}
?>
