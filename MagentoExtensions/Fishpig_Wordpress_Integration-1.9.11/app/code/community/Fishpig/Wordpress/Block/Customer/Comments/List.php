<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Block_Customer_Comments_List extends Mage_Core_Block_Template
{
	protected $_comments = null;
	
	/**
	 * Retrieve a collection of customer comments
	 *
	 * @return Fishpig_Wordpress_Model_Mysql4_Post_Comment_Collection
	 */
	public function getComments()
	{
		return $this->_getCommentCollection();
	}

	/**
	 * Retrieve a collection of customer comments
	 *
	 * @return Fishpig_Wordpress_Model_Mysql4_Post_Comment_Collection
	 */
	protected function _getCommentCollection()
	{
		if (is_null($this->_comments)) {
			$this->_comments = array();
			$user = Mage::getModel('wordpress/user');
			
			if ($user->loadCurrentLoggedInUser()) {
				$this->_comments = Mage::getResourceModel('wordpress/post_comment_collection')
					->addUserIdFilter($user->getId());
			}
		}
		
		return $this->_comments;
	}
	
	/**
	 * Retrieve the name of the current post
	 *
	 * @param Fishpig_Wordpress_Model_Post_Comment $comment
	 * @return string
	 */
	public function getPostName(Fishpig_Wordpress_Model_Post_Comment $comment)
	{
		if ($post = $comment->getPost()) {
			return $post->getPostTitle();
		}
	}

	/**
	 * Retrieve the URL of the current post
	 *
	 * @param Fishpig_Wordpress_Model_Post_Comment $comment
	 * @return string
	 */	
	public function getPostUrl(Fishpig_Wordpress_Model_Post_Comment $comment)
	{
		if ($post = $comment->getPost()) {
			return sprintf('%s#comment-%d', $post->getPermalink(), $comment->getId());
		}

		return '#';
	}
	
	/**
	 * Determine whether the comment is still valid
	 * IF comment has been spammed/trashed, return false
	 *
	 * @param Fishpig_Wordpress_Model_Post_Comment $comment
	 * @return bool
	 */
	public function isCommentValid(Fishpig_Wordpress_Model_Post_Comment $comment)
	{
		return in_array($comment->getCommentApproved(), array('0', '1'));
	}
	
	/**
	 * Retrieve the status of the comment
	 *
	 * @param Fishpig_Wordpress_Model_Post_Comment $comment
	 * @return string
	 */
	public function getCommentStatus(Fishpig_Wordpress_Model_Post_Comment $comment)
	{
		if ($comment->getCommentApproved() == '0') {
			return $this->__('Pending');
		}
		elseif ($comment->getCommentApproved() == '1') {
			return $this->__('Approved');
		}
		
		return $this->__('Not Approved');
	}
}
