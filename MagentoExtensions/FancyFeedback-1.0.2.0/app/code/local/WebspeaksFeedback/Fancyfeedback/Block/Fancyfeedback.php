<?php
class WebspeaksFeedback_Fancyfeedback_Block_Fancyfeedback extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getFancyfeedback()     
     { 
        if (!$this->hasData('fancyfeedback')) {
            $this->setData('fancyfeedback', Mage::registry('fancyfeedback'));
        }
        return $this->getData('fancyfeedback');
        
    }
}