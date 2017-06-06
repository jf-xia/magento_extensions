<?php
class PWS_ProductQA_Block_Adminhtml_Productqa_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_controller = 'productqa';

        $this->_updateButton('save', 'label', Mage::helper('pws_productqa')->__('Save Answer'));
        $this->_updateButton('delete', 'label', Mage::helper('pws_productqa')->__('Delete Question'));

    }

    public function getHeaderText()
    {
        if( Mage::registry('productqa') && Mage::registry('productqa')->getId() ) {
            return Mage::helper('pws_productqa')->__("Edit Answer", $this->htmlEscape(Mage::registry('productqa')->getTitle()));
        } else {
            return Mage::helper('pws_productqa')->__('New Question');
        }
    }
    
    
    /*
    * Overrided method because the way the name of the block form is constructed is wrong for local/community modules
    * Eg: $this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form' => adminhtml/productqa_edit_form
    * we need 'pws_productqa/adminhtml_productqa_edit_form'
    */    
    protected function _prepareLayout()
    { 
        if ($this->_blockGroup && $this->_controller && $this->_mode) {
            $this->setChild('form', $this->getLayout()->createBlock('pws_productqa/adminhtml_productqa_edit_form'));
        }
        return parent::_prepareLayout();
    }
}
