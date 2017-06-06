<?php
class WebspeaksFeedback_Fancyfeedback_Block_Fancyfeedbacksettings extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getFancyfeedbacksettings()     
     { 
        if (!$this->hasData('fancyfeedbacksettings')) {
            $this->setData('fancyfeedbacksettings', Mage::registry('fancyfeedbacksettings'));
        }
        return $this->getData('fancyfeedbacksettings');
        
    }
}