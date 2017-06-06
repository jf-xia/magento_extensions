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
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class AW_Blog_Block_Cat extends Mage_Core_Block_Template
{
    public function getPosts()
   	{	
		$cats = Mage::getSingleton('blog/cat');
		if ($cats->getCatId() == NULL)
		{
			
			return false;
		}
		else
		{
			$page = (int)$this->getRequest()->getParam('page');
			$posts = Mage::getModel('blog/blog')->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->addPresentFilter()
			->addCatFilter($cats->getCatId())
			->setOrder('created_time', 'desc');
			Mage::getSingleton('blog/status')->addEnabledFilterToCollection($posts);
			//Mage::getSingleton('blog/status')->addCatFilterToCollection($posts, $cats->getCatId());
			
			$posts->setPageSize((int)Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_PERPAGE));
        	$posts->setCurPage($page);
			
			$route = Mage::helper('blog')->getRoute();
			
			foreach ($posts as $item) 
			{
				if(Mage::getStoreConfig('blog/blog/categories_urls')){
					$item->setAddress($this->getUrl($route . '/cat/' . $cats->getIdentifier() . '/post/' . $item->getIdentifier()));
				}else{
					 $item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));
				}
				$item->setCreatedTime($this->formatDate($item->getCreatedTime(),Mage::getStoreConfig('blog/blog/dateformat'), true));
				$item->setUpdateTime($this->formatDate($item->getUpdateTime(),Mage::getStoreConfig('blog/blog/dateformat'), true));
				
				if(Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_USESHORTCONTENT) && strip_tags(trim($item->getShortContent()))){
					$content = trim($item->getShortContent());
					$content = $this->closetags($content);
					$content .= ' <a href="' . $this->getUrl($route . "/" . $item->getIdentifier()) . '" >'.$this->__('Read More').'</a>';
					$item->setPostContent($content);
				}elseif ((int)Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_READMORE) != 0)
				{
					$content = $item->getPostContent();
					if(strlen($content) >= (int)Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_READMORE))
					{
						$content = substr($content, 0, (int)Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_READMORE));
						$content = substr($content, 0, strrpos($content, ' '));
						$content = $this->closetags($content);
						$content .= ' <a href="' . $this->getUrl($route . "/" . $item->getIdentifier()) . '" >Read More</a>';
					}
					$item->setPostContent($content);
				}
				
				$comments = Mage::getModel('blog/comment')->getCollection()
				->addPostFilter($item->getPostId())
				
				->addApproveFilter(2);
				$item->setCommentCount(count($comments));
				
				$inCats = Mage::getModel('blog/cat')->getCollection()
				
				->addPostFilter($item->getPostId());
				$catUrls = array();
				foreach($inCats as $cat)
				{
					$catUrls[$cat->getTitle()] = Mage::getUrl($route . "/cat/" . $cat->getIdentifier());
				}
				$item->setCats($catUrls);
				}
				$this->setData('cat', $posts);
				return $this->getData('cat');
		}
        return false;
    }
	
	public function getBookmarkHtml($post)
	{
		if (Mage::getStoreConfig('blog/blog/bookmarkslist'))
		{
			$this->setTemplate('aw_blog/bookmark.phtml');
			$this->setPost($post);
			return $this->toHtml();
		}
		return;
	}
	
	public function getCommentsEnabled()
   	{
		return Mage::getStoreConfig('blog/comments/enabled');
	}
	
	public function getCat()
   	{	
		$cats = Mage::getSingleton('blog/cat');
		return $cats;
	}
	
	public function getPages()
	{
		if ((int)Mage::getStoreConfig('blog/blog/perpage') != 0)
		{
			$collection = Mage::getModel('blog/blog')->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->setOrder('created_time ', 'desc');
			
			$cats = Mage::getSingleton('blog/cat');
			
			Mage::getSingleton('blog/status')->addEnabledFilterToCollection($collection);
			Mage::getSingleton('blog/status')->addCatFilterToCollection($collection, $cats->getCatId());
			
			$currentPage = (int)$this->getRequest()->getParam('page');
			$cat = $this->getRequest()->getParam('identifier');
	
			if(!$currentPage)
			{
				$currentPage = 1;
			}
			
			$route = Mage::helper('blog')->getRoute();
			
			$pages = ceil(count($collection) / (int)Mage::getStoreConfig('blog/blog/perpage'));
			
			$links = "";
			
			if ($currentPage > 1)
			{
				$links = $links . '<div class="left"><a href="' . $this->getUrl($route . '/cat/' . $cat . '/page/' .($currentPage - 1)) . '" >< Newer Posts</a></div>';
			}
			if ($currentPage < $pages)
			{
				$links = $links .  '<div class="right"><a href="' . $this->getUrl($route . '/cat/' . $cat . '/page/' . ($currentPage + 1)) . '" >Older Posts ></a></div>';
			}
			echo $links;
		}
	}
	
	protected function _prepareLayout()
    {
        $post = $this->getCat();
		
		$route = Mage::helper('blog')->getRoute();
		
		// show breadcrumbs
		if (Mage::getStoreConfig('blog/blog/blogcrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))){
				$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('blog')->__('Home'), 'title'=>Mage::helper('blog')->__('Go to Home Page'), 'link'=>Mage::getBaseUrl()));;
				$breadcrumbs->addCrumb('blog', array('label'=>Mage::getStoreConfig('blog/blog/title'), 'title'=>Mage::helper('blog')->__('Return to ' .Mage::getStoreConfig('blog/blog/title')), 'link'=>Mage::getUrl($route)));
				$breadcrumbs->addCrumb('blog_page', array('label'=>$post->getTitle(), 'title'=>$post->getTitle()));
		}
		
		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle($post->getTitle());
			$head->setKeywords($post->getMetaKeywords());
			$head->setDescription($post->getMetaDescription());
		}
	}
	
	public function closetags($html){
		return Mage::helper('blog/post')->closetags($html);
	}
}
