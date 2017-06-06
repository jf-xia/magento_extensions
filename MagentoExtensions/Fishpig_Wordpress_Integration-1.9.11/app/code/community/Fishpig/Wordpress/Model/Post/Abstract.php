<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

abstract class Fishpig_Wordpress_Model_Post_Abstract extends Mage_Core_Model_Abstract
{
	/**
	 * Returns a collection of comments for this post
	 *
	 * @return Fishpig_Wordpress_Model_Mysql4_Post_Comment_Collection
	 */
	public function getComments()
	{
		if (!$this->hasData('comments')) {
			$this->setData('comments', $this->getResource()->getPostComments($this));
		}
		
		return $this->getData('comments');
	}

	/**
	 * Returns a collection of images for this post
	 * 
	 * @return Fishpig_Wordpress_Model_Mysql4_Image_Collection
	 *
	 * NB. This function has not been thoroughly tested
	 *        Please report any bugs
	 */
	public function getImages()
	{
		if (!$this->hasData('images')) {
			$this->setImages(Mage::getResourceModel('wordpress/image_collection')->setParent($this->getData('ID')));
		}
		
		return $this->getData('images');
	}

	/**
	 * Returns the featured image for the post
	 *
	 * This image must be uploaded and assigned in the WP Admin
	 *
	 * @return Fishpig_Wordpress_Model_Image
	 */
	public function getFeaturedImage()
	{
		if (!$this->hasData('featured_image')) {
			$this->setFeaturedImage($this->getResource()->getFeaturedImage($this));
		}
	
		return $this->getData('featured_image');	
	}
	
	/**
	 * Get the model for the author of this post
	 *
	 * @return Fishpig_Wordpress_Model_Author
	 */
	public function getAuthor()
	{
		return Mage::getModel('wordpress/user')->load($this->getAuthorId());	
	}
	
	/**
	 * Returns the author ID of the current post
	 *
	 * @return int
	 */
	public function getAuthorId()
	{
		return $this->getData('post_author');
	}
	
	/**
	 * Returns the post date formatted
	 * If not format is supplied, the format specified in your Magento config will be used
	 *
	 * @return string
	 */
	public function getPostDate($format = null)
	{
		if ($this->getData('post_date_gmt') && $this->getData('post_date_gmt') != '0000-00-00 00:00:00') {
			return Mage::helper('wordpress')->formatDate($this->getData('post_date_gmt'), $format);
		}
	}
	
	/**
	 * Returns the post time formatted
	 * If not format is supplied, the format specified in your Magento config will be used
	 *
	 * @return string
	 */
	public function getPostTime($format = null)
	{
		if ($this->getData('post_date_gmt') && $this->getData('post_date_gmt') != '0000-00-00 00:00:00') {
			return Mage::helper('wordpress')->formatTime($this->getData('post_date_gmt'), $format);
		}
	}
}
