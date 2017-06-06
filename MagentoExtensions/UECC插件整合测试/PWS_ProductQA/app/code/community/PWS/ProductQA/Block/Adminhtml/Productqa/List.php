<?php
class PWS_ProductQA_Block_Adminhtml_Productqa_List extends Mage_Adminhtml_Block_Widget_Container
{
   
    protected $_backButtonLabel = 'Back';
    protected $_blockGroup = 'adminhtml';
    
    
    public function __construct()
    {
        parent::__construct();
        
        $this->_controller = 'productqa';
        $this->_headerText = Mage::helper('pws_productqa')->__('Manage Product Q&A');
       
        
        $this->setTemplate('widget/grid/container.phtml');
    }
    
    protected function _prepareLayout()
    {        
         $this->setChild('store_switcher',
            $this->getLayout()->createBlock('adminhtml/store_switcher')
                ->setUseConfirm(false)
                ->setSwitchUrl($this->getUrl('*/*/*', array('store'=>null)))
                ->setTemplate('store/switcher.phtml')
        );
        
        $this->setChild( 'grid',
            $this->getLayout()->createBlock('pws_productqa/adminhtml_productqa_grid',
            $this->_controller . '.grid')->setSaveParametersInSession(true) );
        return parent::_prepareLayout();
    }

    public function getStoreSwitcherHtml()
    {
        
        return $this->getChildHtml('store_switcher');
    }

    public function getGridHtml()
    {
        $html = $this->getChildHtml('store_switcher');
        $html .=  $this->getChildHtml('grid');
        
        return $html;
    }
    
    protected function _addBackButton()
    {
        $this->_addButton('back', array(
            'label'     => $this->getBackButtonLabel(),
            'onclick'   => 'setLocation(\'' . $this->getBackUrl() .'\')',
            'class'     => 'back',
        ));
    }
    
    
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new');
    }

    public function getHeaderCssClass()
    {
        return 'icon-head ' . parent::getHeaderCssClass();
    }

    public function getHeaderWidth()
    {
        return 'width:50%;';
    }
}
