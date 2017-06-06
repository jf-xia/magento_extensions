<?php
class CommerceStack_Recommender_Model_Resource_Eav_Mysql4_Product_Link_Product_Collection
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Link_Product_Collection
{  
	/**
     * Join linked products and their attributes
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Link_Product_Collection
     */
    protected function _joinLinks()
    {
        $linkResource = $this->getLinkModel()->isLinkSourceManual() ? 'catalog/product_link' : 'recommender/product_link';
        
        $joinCondition = 'links.linked_product_id = e.entity_id AND links.link_type_id = ' . $this->_linkTypeId;
        $joinType = 'join';
        if ($this->getProduct() && $this->getProduct()->getId()) {
            if ($this->_isStrongMode) {
                $this->getSelect()->where('links.product_id = ?', $this->getProduct()->getId());
            }
            else {
                $joinType = 'joinLeft';
                $joinCondition.= ' AND links.product_id = ' . $this->getProduct()->getId();
            }
            $this->getSelect()->where('e.entity_id != ?', $this->getProduct()->getId());
        }
        elseif ($this->_isStrongMode) {
            $this->getSelect()->where('e.entity_id = -1');
        }
        if($this->_hasLinkFilter) {
            $selectCols = $this->getLinkModel()->isLinkSourceManual() ? array('link_id') : array('link_id', 'position', 'count');
            $this->getSelect()->$joinType(
                array('links' => $this->getTable($linkResource)),
                $joinCondition,
                $selectCols
            );
            $this->joinAttributes();
        }
        return $this;
    }
    
    /**
     * Join attributes
     *
     * @return Mage_Catalog_Model_Product
     */
    public function joinAttributes()
    {

        if ($this->getLinkModel()) {
            $attributes = $this->getLinkModel()->getAttributes();
            $attributesByType = array();
            foreach ($attributes as $attribute) {
                if($this->getLinkModel()->isLinkSourceCommerceStack() && $attribute['code'] == 'position') continue;
                    $table = $this->getLinkModel()->getAttributeTypeTable($attribute['type']);
                    $alias = 'link_attribute_'.$attribute['code'].'_'.$attribute['type'];
                    $this->getSelect()->joinLeft(
                        array($alias => $table),
                        $alias.'.link_id=links.link_id AND '.$alias.'.product_link_attribute_id='.$attribute['id'],
                        array($attribute['code'] => 'value')
                    );
            }
        }
        return $this;
    }
    
    public function getSize()
    {
        // 2nd part of OR condition added to fix 20 item limit problem in the admin
        if(is_null($this->isLoaded()) || $this->getLinkModel()->isLinkSourceManual())
        {
            // We haven't loaded the collection yet (probably Admin page). Get size
            // by querying the DB in the usual way (this gets only the size of manually defined links)
            return parent::getSize();
        }
        else 
        {
            $this->_totalRecords = count(array_keys($this->getItems()));
        }
        return intval($this->_totalRecords);
    }
    
    public function getAllIds($limit=null, $offset=null)
    {
        // $limit and $offset are ignored and are for compatibility with parent class only
        return array_keys($this->getItems());
    }
    
    public function clear()
    {
        $this->_items = array();
    }

    
}