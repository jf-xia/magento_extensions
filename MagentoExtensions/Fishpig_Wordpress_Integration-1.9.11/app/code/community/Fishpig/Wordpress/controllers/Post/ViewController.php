<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Post_ViewController extends Fishpig_Wordpress_Controller_Abstract
{
	protected function _init()
	{
		if (!$this->_initPostModel()) {
			return false;
		}

		if ($this->isFeedPage()) {
			$this->_forward('commentFeed');
			return null;
		}
		
		$this->_checkForPostedComment();
		
		parent::_init();
		
		if ($post = $this->_getPost()) {
			$this->_title($post->getPageTitle());
				
			if ($headBlock = $this->getLayout()->getBlock('head')) {
				$headBlock->setDescription($post->getMetaDescription());
				$headBlock->setKeywords($post->getMetaKeywords());
			}
			
			$this->_addCrumb('post', array('label' => $post->getPostTitle()));
			$this->_addCanonicalLink($post->getPermalink());
			
			if ($headBlock = $this->_getBlock('head')) {
				$feedTitle = sprintf('%s &raquo; %s Comments Feed', Mage::helper('wordpress')->getCachedWpOption('blogname'), $post->getPostTitle());
				$headBlock->addItem('link_rel', $post->getCommentFeedUrl(), 'rel="alternate" type="application/rss+xml" title="' . $feedTitle . '"');
			}
		}

		return true;
	}
	
	/**
	 * Returns the current post model
	 *
	 * @return Fishpig_Wordpress_Model_Post
	 */
	protected function _getPost()
	{
		return Mage::registry('wordpress_post');
	}

	protected function _checkForPostedComment()
	{
		if ($response = $this->getRequest()->getParam('cy')) {
			Mage::getSingleton('core/session')->addSuccess($this->__(Mage::getStoreConfig('wordpress_blog/post_comments/success_msg')));
		}
		else if ($response = $this->getRequest()->getParam('cx')) {
			Mage::getSingleton('core/session')->addError($this->__(Mage::getStoreConfig('wordpress_blog/post_comments/error_msg')));
		}

		return $this;
	}

	
	public function commentFeedAction()
	{
		if ($this->isEnabledForStore()) {
			$this->getResponse()
				->setBody(
					$this->getLayout()->createBlock('wordpress/feed_post_comment')->setPost($this->_getPost())->toHtml()
				);
			
			$this->getResponse()->sendResponse();
	
			exit;
		}
		else {
			$this->_forward('noRoute');
		}
	}
	
	/**
	 * Initialise the post model
	 * Provides redirects for Guid links when using permalinks
	 *
	 * @return false|Fishpig_Wordpress_Model_Post
	 */
	protected function _initPostModel()
	{
		$postHelper = Mage::helper('wordpress/post');

		if (!$postHelper->useGuidLinks()) {
			$uri = Mage::helper('wordpress/router')->getBlogUri();

			if ($post = $postHelper->loadByPermalink($uri)) {
				if ($this->getRequest()->getParam($this->getRouterHelper()->getTrackbackVar())) {
					$this->_redirectUrl($post->getUrl());
					$this->getResponse()->sendHeaders();
					exit;
				}

				Mage::register('wordpress_post', $post);
				return $post;
			}

			if ($postId = $postHelper->getPostId()) {
				$post = Mage::getModel('wordpress/post')->load($postId);
				
				if ($post->getId()) {
					if ($this->getRequest()->getParam('preview') && $post->getPostStatus() == 'draft') {
						Mage::register('wordpress_post', $post);
						return $post;
					}

					if ($post->getPostStatus() == 'publish') {
						$this->_redirectUrl($post->getUrl());
						$this->getResponse()->sendHeaders();
						exit;
					}
				}
			}
		}
		else if ($postId = $postHelper->getPostId()) {
			$post = Mage::getModel('wordpress/post')->load($postId);
			
			if ($post->getId()) {
				Mage::register('wordpress_post', $post);
				return $post;
			}
		}
		
		return false;
	}
}
