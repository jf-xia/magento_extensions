<?php
/*
	Frontend_Base_Default_Template_FbLikeBox_Box
*/

class Eisbehr_FbLikeBox_Block_Box extends Mage_Core_Block_Template
{
	public $_helper;
	
	protected function _construct()
	{
		$this->_helper = Mage::helper('fblikebox');
		return;
	}
}