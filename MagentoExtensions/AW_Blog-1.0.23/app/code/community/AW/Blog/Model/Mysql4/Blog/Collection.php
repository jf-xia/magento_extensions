<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-L.txt
 *
 * @category   AW
 * @package    AW_Blog
 * @copyright  Copyright (c) 2009-2011 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class AW_Blog_Model_Mysql4_Blog_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('blog/blog');
            
    }
    
    public function addEnableFilter($status)
    {
        $this->getSelect()
            ->where('status = ?', $status);
        return $this;
    }
    
    public function addPresentFilter(){
         $this->getSelect()
            ->where('created_time<=?', now());
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

    public function addCatsFilter($catIds)
    {
        $this->getSelect()->join(
            array('cat_table' => $this->getTable('post_cat')),
            'main_table.post_id = cat_table.post_id',
            array()
        )
        ->where('cat_table.cat_id IN ('.$catIds.')')
        ->group('cat_table.post_id')
        ;

        return $this;
    }

    /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return Mage_Cms_Model_Mysql4_Page_Collection
     */
    public function addStoreFilter($store = null)
    {
        if($store === null)
            $store = Mage::app()->getStore()->getId();
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }
    
            $this->getSelect()->joinLeft(
                array('store_table' => $this->getTable('store')),
                'main_table.post_id = store_table.post_id',
                array()
            )
            ->where('store_table.store_id in (?)', array(0, $store));
    
            return $this;
        }
        return $this;
    }
    
    public function addTagFilter($tag){
        if($tag = trim($tag)) {
            $whereString = sprintf("main_table.tags = %s OR main_table.tags LIKE %s OR main_table.tags LIKE %s OR main_table.tags LIKE %s",
                    $this->getConnection()->quote($tag),
                    $this->getConnection()->quote($tag.',%'),
                    $this->getConnection()->quote('%,'.$tag),
                    $this->getConnection()->quote('%,'.$tag.',%')
                );
            $this->getSelect()->where($whereString);
        }
        return $this;
    }
}
