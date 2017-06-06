<?php
class CommerceStack_Recommender_Block_Catalog_Product_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        $this->addTab('commercestack_related', array(
                'label'     => Mage::helper('catalog')->__('Related Products (Automated)'),
                'url'       => $this->getUrl('recommender/product/related', array('_current' => true)), //'commercestack_related' => true)),
                'class'     => 'ajax',
                'insertAfter' => 'related',
            ));

        // Upsell source is based on user-config
        $upsellSource = Mage::getStoreConfig('recommender/relatedproductsadvanced/upsellsource');
        $tabUrls = array('related' => 'recommender/product/related', 'crosssell' => 'recommender/product/crosssell', 'random' => '*/*/upsell');
        
        $this->addTab('commercestack_upsell', array(
                'label'     => Mage::helper('catalog')->__('Up-sells (Automated)'),
                'url'       => $this->getUrl($tabUrls[$upsellSource], array('_current' => true)), //'commercestack_upsell' => true)),
                'class'     => 'ajax',
                'insertAfter' => 'upsell',
            ));
             
        $this->addTab('commercestack_crosssell', array(
            'label'     => Mage::helper('catalog')->__('Cross-sells (Automated)'),
            'url'       => $this->getUrl('recommender/product/crosssell', array('_current' => true)), //'commercestack_crosssell' => true)),
            'class'     => 'ajax',
            'insertAfter' => 'crosssell',
        ));
    }
    
    public function addTab($tabId, $tab)
    {
        if(isset($tab['insertAfter']))
        {
            // Remove and remember every tab after the specified key
            $afterTabs = array();
            $afterKeyFound = false;
            foreach($this->_tabs as $key => $value)
            { 
                if($afterKeyFound)
                {
                    $afterTabs[$key] = $value;
                    unset($this->_tabs[$key]);
                }
                
                if($key == $tab['insertAfter']) $afterKeyFound = true;
            }
        }
        
        parent::addTab($tabId, $tab);
        
        if(isset($tab['insertAfter']))
        {
            // Now that we've added our new tab, add the remembered tabs back into the internal array
            $this->_tabs += $afterTabs;
        }
    }
}