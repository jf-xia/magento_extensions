<?php

/* ---------------------------------------------------------------------------------------------------------- */
/* FOR DEVELOPERS ONLY                                                                                        */
/* ---------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------- */
/* * ************ DO NOT CHANGE THESE LINES **************                                        */
/* ---------------------------------------------------------------------------------------------------------- */

class Wyomind_Datafeedmanager_Model_MyCustomAttributes extends Wyomind_Datafeedmanager_Model_Configurations {
    
	
	public function __construct(){
		$this->_attributes = Mage::getModel('datafeedmanager/attributes')->getCollection();
	}
	
	/* --------------------------------------------------------------------------------------------------------- */
    /* this method retrieves the available custom attributes into the library                                    */
    /* --------------------------------------------------------------------------------------------------------- */
   
    public function _getAll() {

       
        $attr = array();
        foreach ($this->_attributes as $attribute) {
            $attr['Custom Attributes'][] = $attribute->getAttributeName();
        }
        return $attr;
    }

    
    /* ---------------------------------------------------------------------------------------------------------- */
    /* this method transforms the custom attributes to a computed value                                           */
    /* ---------------------------------------------------------------------------------------------------------- */

    public function  _eval($product, $exp, $value) {
        
       

        foreach ($this->_attributes as $attribute) {

            if ($exp['pattern'] == "{" . $attribute->getAttributeName() . "}") {

                eval(str_replace('return', '$value =', $attribute->getAttributeScript()));
            }
            
        }
        return $value;
    }

}
