<?php

class Magestore_Bannerslider_Helper_Data extends Mage_Core_Helper_Abstract
{
	const DISP_HOME_PAGE = '0';
	const DISP_CATEGORY = '1';
	
	public function getDisplayOption(){
		return array(
			array('value'=>self::DISP_HOME_PAGE, 'label'=>$this->__('Home page')),
			array('value'=>self::DISP_CATEGORY, 'label'=>$this->__('Category')),
		);
	}
}