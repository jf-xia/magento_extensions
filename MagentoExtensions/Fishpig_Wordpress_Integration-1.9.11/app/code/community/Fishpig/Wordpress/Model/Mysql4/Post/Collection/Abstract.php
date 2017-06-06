<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

abstract class Fishpig_Wordpress_Model_Mysql4_Post_Collection_Abstract extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	/**
	 * Return the current post type (post || page)
	 *
	 * @return string
	 */
	protected function _getPostType()
	{
		return trim(substr($this->getResourceModelName(), strpos($this->getResourceModelName(), '/')), '/');
	}
	
	/**
	 * Ensures that only posts and not pages are returned
	 * WP stores posts and pages in the same DB table
	 *
	 */
    protected function _initSelect()
    {
    	parent::_initSelect();

        $this->getSelect()->where("`main_table`.`post_type`=?", $this->_getPostType());

		return $this;
	}

	/**
	 * Adds a published filter to collection
	 *
	 */
	public function addIsPublishedFilter()
	{
		return $this->addStatusFilter('publish');
	}
	
	/**
	 * Adds a filter to the status column
	 *
	 * @param string $status
	 */
	public function addStatusFilter($status)
	{
		$this->getSelect()
			->where('`main_table`.`post_status` =?', $status);
			
		return $this;
	}
	
	/**
	 * Sets the current page based on the URL value
	 */
	public function setPageFromUrl()
	{
		$pageId = Mage::app()->getRequest()->getParam('page', 1);
		return $this->setCurPage($pageId);
	}
	
	/**
	 * Sets the number of posts per page
	 * If no value is passed, the number of posts is taken from the WP Admin Config
	 *
	 * @param int $postsPerPage
	 */
	public function setPostsPerPage($postsPerPage = null)
	{
		if (is_null($postsPerPage)) {
			$postsPerPage = Mage::app()->getRequest()->getParam('limit', Mage::helper('wordpress')->getCachedWpOption('posts_per_page', 10));
		}

		return $this->setPageSize($postsPerPage);
	}
	
	/**
	 * Filter the collection by an author ID
	 *
	 * @param int $authorId
	 */
	public function addAuthorIdFilter($authorId)
	{
		return $this->addFieldToFilter('post_author', $authorId);
	}
	
	/**
	 * Orders the collection by post date
	 *
	 * @param string $dir
	 */
	public function setOrderByPostDate($dir = 'desc')
	{
		return $this->setOrder('post_date', $dir);
	}
}
