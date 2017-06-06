<?php
class Wyomind_Datafeedmanager_Block_Adminhtml_Options extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_options';
		$this->_blockGroup = 'datafeedmanager';
		$this->_headerText = Mage::helper('datafeedmanager')->__('Data Feed Manager');
		$this->_addButtonLabel = Mage::helper('datafeedmanager')->__('Create a new custom option');
		parent::__construct();
	}
}


