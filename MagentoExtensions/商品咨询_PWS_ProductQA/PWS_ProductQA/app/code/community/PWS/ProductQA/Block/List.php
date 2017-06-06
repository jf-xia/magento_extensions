<?php
class PWS_ProductQA_Block_List extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();
        
        $productqaEntries = Mage::getModel('pws_productqa/productqa')->getCollection()
            ->addFieldToFilter('status', 'public')
            ->addFieldToFilter('answered_on', array('notnull' => true))
            ->addFieldToFilter('product_id', Mage::registry('product')->getId())
            ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
            ->setOrder('created_on','DESC')
        ;

        $this->setEntries($productqaEntries);

    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'productqa.pager');
        $pager->setAvailableLimit(array(5=>5, 10=>10, 20=>20, 50=>50));    
        $pager->setCollection($this->getEntries());
        
        $this->setChild('pager', $pager);
        $this->getEntries()->load();
               
        
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}
