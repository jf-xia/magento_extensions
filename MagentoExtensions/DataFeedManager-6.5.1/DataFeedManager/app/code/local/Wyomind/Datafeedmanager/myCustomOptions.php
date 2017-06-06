<?php

class Wyomind_Datafeedmanager_Model_MyCustomOptions extends Wyomind_Datafeedmanager_Model_Configurations {
    
	public function __construct(){
		 $this->_options = Mage::getModel('datafeedmanager/options')->getCollection();
	}
	
	
	/* --------------------------------------------------------------------------------------------------------- */
    /* this method retrieves the available custom attributes into the library                                    */
    /* --------------------------------------------------------------------------------------------------------- */

    public function _getAll() {

       
        $attr = array();
        foreach ($this->_options as $option) {
            $attr['Custom Options'][] = $option->getOptionName();
        }
        return $attr;
    }

    /* ---------------------------------------------------------------------------------------------------------- */
    /* this method transforms the custom attributes to a computed value                                           */
    /* ---------------------------------------------------------------------------------------------------------- */

    public function _eval($product, $exp, $value) {

       
        $found = false;

        foreach ($this->_options as $option) {

            if ($exp['options'][$this->option] == "" . $option->getOptionName() . "") {

                for ($i = 0; $i <= $option->getOptionParam(); $i++) {
                    $param[$i] = $exp['options'][$this->option + $i];
                }

                eval(str_replace('return', '$value =', $option->getOptionScript()));
                 
                $this->skipOptions(1 + $option->getOptionParam());
                $found = true;
              
               
            }
        }
        if (!$found) {
            eval('$value=' . $exp['options'][$this->option] . '($value);');
            $this->skipOptions(1);
        }
        return $value;
    }

}
