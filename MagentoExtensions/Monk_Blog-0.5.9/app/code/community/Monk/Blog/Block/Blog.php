<?php
class Monk_Blog_Block_Blog extends Mage_Core_Block_Template
{
    public function getPosts()
   	{
        $collection = Mage::getModel('blog/blog')->getCollection()
		->addStoreFilter(Mage::app()->getStore()->getId())
		->setOrder('created_time ', 'desc');

		$page = $this->getRequest()->getParam('page');

		Mage::getSingleton('blog/status')->addEnabledFilterToCollection($collection);
		
		$collection->setPageSize((int)Mage::getStoreConfig('blog/blog/perpage'));
        $collection->setCurPage($page);
		
		$route = Mage::getStoreConfig('blog/blog/route');
		if ($route == "")
		{
			$route = "blog";
		}
		
		foreach ($collection as $item) 
		{
			$item->setAddress($this->getUrl($route) . $item->getIdentifier());
			
			$item->setCreatedTime($this->formatTime($item->getCreatedTime(),Mage::getStoreConfig('blog/blog/dateformat'), true));
			$item->setUpdateTime($this->formatTime($item->getUpdateTime(),Mage::getStoreConfig('blog/blog/dateformat'), true));
			
			if ((int)Mage::getStoreConfig('blog/blog/readmore') != 0)
			{
				$content = $item->getPostContent();
				if(strlen($content) >= (int)Mage::getStoreConfig('blog/blog/readmore'))
				{
					$content = substr($content, 0, (int)Mage::getStoreConfig('blog/blog/readmore'));
					$content = substr($content, 0, strrpos($content, ' '));
					$content = $this->closetags($content);
					$content .= ' ...&nbsp;&nbsp;<a href="' . $this->getUrl($route) . $item->getIdentifier() . '" >Read More</a>';
				}
				$item->setPostContent($content);
			}
			$comments = Mage::getModel('blog/comment')->getCollection()
			->addPostFilter($item->getPostId())
			->addApproveFilter(2);
			$item->setCommentCount(count($comments));
			
			$cats = Mage::getModel('blog/cat')->getCollection()
			->addPostFilter($item->getPostId());
			$catUrls = array();
			foreach($cats as $cat)
			{
				$catUrls[$cat->getTitle()] = Mage::getUrl($route . "/cat/") . $cat->getIdentifier();
			}
			$item->setCats($catUrls);
		}
		return $collection;
    }
	
	public function getBookmarkHtml($post)
	{
		if (Mage::getStoreConfig('blog/blog/bookmarkslist'))
		{
			$this->setTemplate('blog/bookmark.phtml');
			$this->setPost($post);
			return $this->toHtml();
		}
		return;
	}
	
	public function getCommentsEnabled()
   	{
		return Mage::getStoreConfig('blog/comments/enabled');
	}
	
	public function getPages()
	{
		if ((int)Mage::getStoreConfig('blog/blog/perpage') != 0)
		{
			$collection = Mage::getModel('blog/blog')->getCollection()
			->setOrder('created_time ', 'desc');
			
			Mage::getSingleton('blog/status')->addEnabledFilterToCollection($collection);
			
			$currentPage = (int)$this->getRequest()->getParam('page');
	
			if(!$currentPage)
			{
				$currentPage = 1;
			}
			
			$pages = ceil(count($collection) / (int)Mage::getStoreConfig('blog/blog/perpage'));
			
			$links = "";
			
			$route = Mage::getStoreConfig('blog/blog/route');
			if ($route == "")
			{
				$route = "blog";
			}
			
			if ($currentPage > 1)
			{
				$links = $links . '<div class="left"><a href="' . $this->getUrl($route. '/page') . ($currentPage - 1) . '" >< Newer Posts</a></div>';
			}
			if ($currentPage < $pages)
			{
				$links = $links .  '<div class="right"><a href="' . $this->getUrl($route .'/page') . ($currentPage + 1) . '" >Older Posts ></a></div>';
			}
			echo $links;
		}
	}
	
	public function getRecent()
   	{
		if (Mage::getStoreConfig('blog/blog/recent') != 0)
		{
			$collection = Mage::getModel('blog/blog')->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->setOrder('created_time ', 'desc');
			
			$route = Mage::getStoreConfig('blog/blog/route');
			if ($route == "")
			{
				$route = "blog";
			}
			
			Mage::getSingleton('blog/status')->addEnabledFilterToCollection($collection);
			$collection->setPageSize(Mage::getStoreConfig('blog/blog/recent'));
			$collection->setCurPage(1);
			foreach ($collection as $item) 
			{
				$item->setAddress($this->getUrl($route) . $item->getIdentifier());
			}
			return $collection;
		}
		else
		{
			return false;
		}
    }
	
	public function getCategories()
   	{
        $collection = Mage::getModel('blog/cat')->getCollection()
		->addStoreFilter(Mage::app()->getStore()->getId())
		->setOrder('sort_order ', 'asc');
		
		$route = Mage::getStoreConfig('blog/blog/route');
		if ($route == "")
		{
			$route = "blog";
		}
		
		foreach ($collection as $item) 
		{
			$item->setAddress($this->getUrl($route . "/cat") . $item->getIdentifier());
		}
		return $collection;
    }
	
	public function addTopLink()
    {
		$route = Mage::getStoreConfig('blog/blog/route');
		if ($route == "")
		{
			$route = "blog";
		}
		$title = Mage::getStoreConfig('blog/blog/title');
        $this->getParentBlock()->addLink($title, $route, $title, true, array(), 15, null, 'class="top-link-blog"');
    }
	public function addFooterLink()
    {
		$route = Mage::getStoreConfig('blog/blog/route');
		if ($route == "")
		{
			$route = "blog";
		}
		$title = Mage::getStoreConfig('blog/blog/title');
        $this->getParentBlock()->addLink($title, $route, $title, true);
    }
	
	public function closetags($html)
	{
		#put all opened tags into an array
		preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
		$openedtags = $result[1];
	
		#put all closed tags into an array
		preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
		$closedtags = $result[1];
		$len_opened = count ( $openedtags );
		# all tags are closed
		if( count ( $closedtags ) == $len_opened )
		{
			return $html;
		}
		$openedtags = array_reverse ( $openedtags );
		# close tags
		for( $i = 0; $i < $len_opened; $i++ )
		{
			if ( !in_array ( $openedtags[$i], $closedtags ) )
			{
				$html .= "</" . $openedtags[$i] . ">";
			}
			else
			{
				unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
			}
		}
		return $html;
	}
}