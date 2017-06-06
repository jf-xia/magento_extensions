<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Block_Post_List_Wrapper_Abstract extends Mage_Core_Block_Template
{
	/**
	 * Cache for post collection
	 *
	 * @var Fishpig_Wordpress_Model_Mysql4_Post_Collection
	 */
	protected $_postCollection = null;

	/**
	 * Block name for the post list block
	 *
	 * @var string
	 */
	protected $_postListBlockName = 'wordpress_post_list';	
	
	/**
	 * Instructs whether to set pagination and page size automatically
	 * collection size is set in the Wordpress Admin
	 * Page ID will be taken from the URL
	 *
	 * @var bool
	 */
	protected $_autoPaginate = false;

	/**
	  * Constructor
	  * This sets the default template for listing the posts
	  * This is not the template for this (wrapper)
	  *
	  */
	public function __construct()
	{
		parent::__construct();
		// Set the default template to list the posts
		$this->setPostListTemplate('wordpress/post/list.phtml');
	}
	
	/**
	 * Returns the collection of posts
	 *
	 * @return Fishpig_Wordpress_Model_Mysql4_Post_Collection
	 */
	public function getPostCollection()
	{
		return $this->_getPostCollection();
	}
	
	/**
	 * Generates and returns the collection of posts
	 *
	 * @return Fishpig_Wordpress_Model_Mysql4_Post_Collection
	 */
	protected function _getPostCollection()
	{
		$collection = Mage::getResourceModel('wordpress/post_collection')
			->addIsPublishedFilter()
			->setPostsPerPage()
			->setPageFromUrl()
			->setOrderByPostDate();

		return $collection;
	}

	/**
	 * Returns the HTML for the post collection
	 *
	 * @return string
	 */
	public function getPostListHtml()
	{
		return $this->getPostListBlock()->toHtml();
	}
	
	/**
	 * Gets the post list block
	 *
	 * @return Fishpig_Wordpress_Block_Post_List
	 */
	public function getPostListBlock()
	{
		if (!$this->hasData('post_list_block')) {
			if ($block = $this->getChild($this->_postListBlockName)) {
				$this->setData('post_list_block', $block->setWrapperBlock($this));
			}
			else {
				$this->setData('post_list_block', $this->_createPostListBlock());
			}
		}
		
		return $this->getData('post_list_block');
	}
	
	/**
	 * Dynamically create the post list block
	 *
	 * @return Fishpig_Wordpress_Block_Post_List
	 */
	protected function _createPostListBlock()
	{
		return $this->getLayout()
			->createBlock('wordpress/post_list', $this->_postListBlockName.microtime().rand(1, 999))
			->setTemplate($this->getPostListTemplate())
			->setWrapperBlock($this);
	}

	/**
	 * Enables auto pagination
	 */
	public function enableAutoPagination()
	{
		$this->_autoPaginate = true;
	}
	
	/**
	 * Disables auto pagination
	 */
	public function disableAutoPagination()
	{
		$this->_autoPaginate = false;
	}
	
	/**
	 * Returns true if auto pagination set
	 *
	 * @return bool
	 */
	public function isAutoPaginationEnabled()
	{
		return $this->_autoPaginate;
	}
	
	/**
	 * Sets the name of the child block that contains the post list
	 *
	 * @param string $blockName
	 */
	public function setPostListBlockName($blockName)
	{
		$this->_postListBlockName = $blockName;
		return $this;
	}
}
