<?php

class WebspeaksFeedback_Fancyfeedback_Model_Fancyfeedback extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('fancyfeedback/fancyfeedback');
    }
}