<?php

class WebspeaksFeedback_Fancyfeedback_Model_Mysql4_Fancyfeedback extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the fancyfeedback_id refers to the key field in your database table.
        $this->_init('fancyfeedback/fancyfeedback', 'fancyfeedback_id');
    }
}