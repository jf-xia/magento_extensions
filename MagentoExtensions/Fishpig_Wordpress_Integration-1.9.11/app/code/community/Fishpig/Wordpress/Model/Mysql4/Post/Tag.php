<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Model_Mysql4_Post_Tag extends Fishpig_Wordpress_Model_Mysql4_Category_Abstract
{
	public function _construct()
	{
		$this->_init('wordpress/post_tag', 'term_id');
	}
	
	/**
	 * Retrieve an array of ID's to be used in the tag cloud
	 *
	 * @return array
	 */
	public function getCloudTagIds()
	{
		$tags = Mage::getResourceModel('wordpress/post_tag_collection')
			->addOrderByCount();
			
		if ($maxTagsToDisplay = Mage::getStoreConfig('wordpress_blog/tag_cloud/max_tags_to_display')) {
			$tags->getSelect()->limit($maxTagsToDisplay);
		}
		
		$tags->getSelect()->setPart('columns', array());
		$tags->getSelect()->columns(array('main_table.term_id'));		

		return $this->_getReadAdapter()->fetchCol($tags->getSelect());
	}
}
