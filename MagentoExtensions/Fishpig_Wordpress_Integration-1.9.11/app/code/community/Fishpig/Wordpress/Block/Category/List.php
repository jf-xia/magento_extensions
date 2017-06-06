<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Block_Category_List extends Mage_Core_Block_Template
{
	/**
	 * Cache for category collection
	 *
	 * @var Fishpig_Wordpress_Model_Mysql4_Category_Collection $_categoryCollection
	 */
	protected $_categoryCollection = null;

	/**
	 * Returns the current category collection
	 */
	public function getCategories()
	{
		if (is_null($this->_categoryCollection)) {
			$collection = Mage::getResourceModel('wordpress/post_category_collection')
				->addParentIdFilter($this->getParentId());
			
			$collection->getSelect()->order('name ASC');

			$this->setCollection($collection);
		}
		
		return $this->_categoryCollection;
	}

	/**
	 * Manually set the category collection
	 *
	 * @param Fishpig_Wordpress_Model_Mysql4_Category_Collection $collection
	 */
	public function setCollection(Fishpig_Wordpress_Model_Mysql4_Category_Collection_Abstract $collection)
	{
		$this->_categoryCollection = $collection;
		return $this;
	}
	
	/**
	 * Returns the parent ID used to display categories
	 * If parent_id is not set, 0 will be returned and root categories displayed
	 *
	 * @return int
	 */
	public function getParentId()
	{
		return number_format($this->getData('parent_id'), 0, '', '');
	}
}
