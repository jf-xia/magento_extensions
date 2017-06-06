<?php

class CommerceStack_Recommender_Model_Product_Link extends Mage_Catalog_Model_Product_Link
{
    const LINK_SOURCE_MANUAL  = 1;
    const LINK_SOURCE_IGNITE  = 2;
    
    protected $_recTypes = array(self::LINK_TYPE_CROSSSELL => 'marketbasket', self::LINK_TYPE_RELATED => 'alsoviewed');
	protected $_linkSource = self::LINK_SOURCE_MANUAL;
	protected $_collectionAsXml;

    public function update()
    {
        $dataHelper = Mage::helper('recommender');
        
        foreach($this->_recTypes as $linkType => $rootName)
        {
            $this->_linkType = $linkType;
            $this->_rootName = $rootName;
            $xml = $dataHelper->getUpdateXml($rootName);
            
            if($xml != '')
            {
                $this->_collectionAsXml = simplexml_load_string($xml);
                
                $this->setHasDataChanges(true);
                $this->_getResource()->saveByRef($this);
            }
        }
    }
    
    // These arguments are all dummies to remain compatible with Varien_Object::toXml()
    public function toXml(array $arrAttributes = array(), $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        return $this->_collectionAsXml;
    }
    
    public function getRootName()
    {
        return $this->_rootName;
    }
    
    public function getLinkType()
    {
        return $this->_linkType;
    }
    
    public function useLinkSourceManual()
    {
        $this->_linkSource = self::LINK_SOURCE_MANUAL;
        return $this;
    }
    
    public function useLinkSourceCommerceStack()
    {
        $this->_linkSource = self::LINK_SOURCE_IGNITE;
        return $this;
    }
    
    public function isLinkSourceManual()
    {
        if($this->_linkSource == self::LINK_SOURCE_MANUAL) return true;
        return false;
    }
    
    public function isLinkSourceCommerceStack()
    {
        if($this->_linkSource == self::LINK_SOURCE_IGNITE) return true;
        return false;
    }

    public function updateFromXml($xml, $linkType)
    {
        $this->_linkType = $linkType;
        $this->_rootName = $this->_recTypes[$linkType];
        $this->_collectionAsXml = $xml;
        $this->setHasDataChanges(true);
        $this->_getResource()->saveByRef($this);
    }
}