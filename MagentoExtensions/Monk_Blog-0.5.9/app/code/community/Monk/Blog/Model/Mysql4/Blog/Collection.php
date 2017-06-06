<?php

class Monk_Blog_Model_Mysql4_Blog_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('blog/blog');
    }
	
	public function addEnableFilter($status)
    {
        $this->getSelect()
            ->where('status = ?', $status);
        return $this;
    }
	
	public function addCatFilter($catId)
    {
		$this->getSelect()->join(
			array('cat_table' => $this->getTable('post_cat')),
			'main_table.post_id = cat_table.post_id',
			array()
		)
		->where('cat_table.cat_id = ?', $catId);

		return $this;
    }

    /*protected function _afterLoad()
    {
		$items = $this->getColumnValues('post_id');
		if (count($items)) {
			$select = $this->getConnection()->select()
					->from($this->getTable('store'))
					->where($this->getTable('store').'.post_id IN (?)', $items);
			if ($result = $this->getConnection()->fetchPairs($select)) {
				foreach ($this as $item) {
					if (!isset($result[$item->getData('post_id')])) {
						continue;
					}
					if ($result[$item->getData('post_id')] == 0) {
						$storeCode = key(Mage::app()->getStores(false, true));
					} else {
						$storeCode = Mage::app()->getStore($result[$item->getData('post_id')])->getCode();
					}
					$item->setData('store_code', $storeCode);
				}
			}
		}

        parent::_afterLoad();
    }*/

    /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return Mage_Cms_Model_Mysql4_Page_Collection
     */
    public function addStoreFilter($store)
    {
		if (!Mage::app()->isSingleStoreMode()) {
			if ($store instanceof Mage_Core_Model_Store) {
				$store = array($store->getId());
			}
	
			$this->getSelect()->join(
				array('store_table' => $this->getTable('store')),
				'main_table.post_id = store_table.post_id',
				array()
			)
			->where('store_table.store_id in (?)', array(0, $store));
	
			return $this;
		}
		return $this;
	}
}