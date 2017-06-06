<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Block_Post_Comment_Recent extends Mage_Core_Block_Template 
{
	/**
	 * Retrieve the recent comments collection
	 *
	 * @return Fishpig_Wordpress_Model_Mysql4_Post_Comment_Collection
	 */
	public function getComments()
	{
		if (!$this->hasComments()) {
			$comments = Mage::getResourceModel('wordpress/post_comment_collection')
				->addCommentApprovedFilter()
				->addOrderByDate('desc');
			
			$comments->getSelect()->limit($this->getCommentCount() ? $this->getCommentCount() : 5 );
			
			$this->setData('comments', $comments);
		}
		
		return $this->getData('comments');
	}
}
