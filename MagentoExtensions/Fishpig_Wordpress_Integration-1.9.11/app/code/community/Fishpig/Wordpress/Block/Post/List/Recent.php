<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Block_Post_List_Recent extends Fishpig_Wordpress_Block_Post_List_Wrapper_Abstract
{
	/**
	 * Set the number of posts to display to 5
	 * This can be overridden using self::setPostCount($postCount)
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_pagerLimit = 5;
		$this->setPostListTemplate('wordpress/post/recent/list.phtml');
		$this->setTitle('Recent Posts');
	}

	/**
	 * Sets the number of posts to display
	 *
	 * @param string $postCount
	 */
	public function setPostCount($postCount = 5)
	{
		$this->_pagerLimit = $postCount;
		return $this;
	}

	/**
	 * Adds on cateogry/author ID filters
	 *
	 * @return Fishpig_Wordpress_Model_Mysql4_Post_Collection
	 */
	protected function _getPostCollection()
	{
		$collection = parent::_getPostCollection()
			->setPageSize($this->_pagerLimit)
			->setCurPage(1);

		if ($categoryId = $this->getData('category_id')) {
			$collection->addCategoryIdFilter($categoryId);
		}
		
		if ($authorId = $this->getData('author_id')) {
			$collection->addAuthorIdFilter($authorId);
		}

		return $collection;
	}
}
