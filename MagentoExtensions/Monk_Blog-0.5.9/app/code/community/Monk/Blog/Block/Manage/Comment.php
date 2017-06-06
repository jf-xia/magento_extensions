<?php
class Monk_Blog_Block_Manage_Comment extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'manage_comment';
    $this->_blockGroup = 'blog';
    $this->_headerText = Mage::helper('blog')->__('Blog Comment Manager');
    parent::__construct();
	$this->_removeButton('add');
  }
}