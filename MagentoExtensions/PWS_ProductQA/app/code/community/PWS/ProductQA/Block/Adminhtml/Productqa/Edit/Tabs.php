<?php
class PWS_ProductQA_Block_Adminhtml_Productqa_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('productqa_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('pws_productqa')->__('General'));
    }
    
    protected function _prepareLayout()
    {
        /*$this->getLayout()->getBlock('head')
            ->addJs('pws/relatedproductsets/productLink.js');*/

        parent::_prepareLayout();
    }
   

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('pws_productqa')->__('General'),
            'title'     => Mage::helper('pws_productqa')->__('General'),
            'content'   => $this->getLayout()->createBlock('pws_productqa/adminhtml_productqa_edit_tab_form')->toHtml(),
        ));
        
       
        return parent::_beforeToHtml();
    }
}
