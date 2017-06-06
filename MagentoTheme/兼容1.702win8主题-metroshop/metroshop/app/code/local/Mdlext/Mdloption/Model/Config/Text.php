<?php
class Mdlext_Mdloption_Model_Config_Text
{
	private $categoryId = "1,2,3";

    public function toOptionArray()
    {
	    $catId = explode(',', $this->categoryId);
	    $options = array();
	    foreach ($catId as $g ){
		    $options[] = array(
			    'value' => $g,
			    'label' => $g,
		    );
	    }

        return $options;
    }

}
