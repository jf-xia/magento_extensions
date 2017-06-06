<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Helper_Post extends Fishpig_Wordpress_Helper_Abstract
{
	/**
	 * Variable used for post ID's when using Guid links
	 *
	 * @var string
	 */
	protected $_postIdVar = 'p';
	/**
	 * Returns TRUE is ?p=id links are being used
	 *
	 * @return bool
	 */
	public function useGuidLinks()
	{
		return !trim($this->getCachedWpOption('permalink_structure'), '/ -');
	}
	
	/**
	 * Returns the permalink structure stored in the WP database
	 *
	 * @return string
	 */
	protected function _getPermalinkStructure()
	{
		if (!$this->useGuidLinks()) {
			return trim($this->getCachedWpOption('permalink_structure'), ' /');
		}
		
		return false;
	}
	
	/**
	 * Retrieve the permalink structure in array format
	 * Each different section is separated
	 *
	 * @return array
	 */
	protected function _getExplodedPermalinkStructure()
	{
		if ($this->useGuidLinks()) {
			return array();
		}
		else {
			$structure = $this->_getPermalinkStructure();
			$parts = preg_split("/(\/)/", $structure, -1, PREG_SPLIT_DELIM_CAPTURE);
			$structure = array();

			foreach($parts as $part) {
				if ($result = preg_split("/(%[a-zA-Z0-9]{1,}%)/", $part, -1, PREG_SPLIT_DELIM_CAPTURE)) {
					$results = array_filter(array_unique($result));
					
					foreach($results as $result) {
						array_push($structure, $result);
					}
				}
				else {
					$structure[] = $part;
				}
			}

			return $structure;
		}
	}

	/**
	 * Retrieve the pattern used to match the URL to the permalink structure
	 *
	 * @return string
	 */
	protected function _getPermalinkPattern()
	{
		$routerHelper = Mage::helper('wordpress/router');
		
		if ($structure = $this->_getExplodedPermalinkStructure()) {

			foreach($structure as $i => $part) {
				if (preg_match('/^\%[a-zA-Z0-9_-]{1,}\%$/', $part)) {
					$part = trim($part, '%');
					
					switch($part) {
						case 'year': 		$part = '[1-2]{1}[0-9]{3}';		break;
						case 'monthnum':	$part = '[0-1]{1}[0-9]{1}';		break;
						case 'day':			$part = '[0-3]{1}[0-9]{1}';		break;
						case 'hour':		$part = '[0-2]{1}[0-9]{1}';		break;
						case 'minute':		$part = '[0-5]{1}[0-9]{1}';		break;
						case 'second':		$part = '[0-5]{1}[0-9]{1}';		break;
						case 'post_id':		$part = '[0-9]{1,}';			break;
						case 'postname':	$part = $routerHelper->getPermalinkStringRegex();	break;
						case 'category':	$part = $routerHelper->getPermalinkStringRegex();	break;
						case 'author':		$part = $routerHelper->getPermalinkStringRegex();	break;
					}
					
					$part = '(' . $part . ')';
				}
				else {
					$part = preg_replace('/([.|\\\|\/]{1})/i', '\\\$1', $part);
				}

				$structure[$i] = $part;
			}

			return '^' . implode('', $structure) . '$';
		}
		
		return false;
	}


	
	/**
	 * Retrieve an array of the 'tokens' from the permalink structure
	 * A token is part of the structure that relates to dynamic post informat
	 * eg. %post_id% or %postname% etc
	 *
	 * @return false|array
	 */
	protected function _getPermalinkTokens()
	{
		if ($format = $this->_getExplodedPermalinkStructure()) {
			foreach($format as $i => $part) {
				if (!preg_match("/^\%([a-zA-Z0-9_\-]{1,})\%$/", $part)) {
					unset($format[$i]);
				}
				else {
					$format[$i] = trim($part, '%');
				}
			}
		
			return array_values($format);
		}
		
		return false;
	}
	
	/**
	 * Determine whether the URI is a post URI
	 * This function accepts URI's generated by Fishpig_Wordpress_Helper_Router::getBlogUri
	 *
	 * @param string
	 * @return bool
	 */
	public function isPostUri($uri, $returnParts = false)
	{
		if ($pattern = $this->_getPermalinkPattern()) {
			$results = array();

			if (preg_match("/" . $pattern . "/iu", $uri, $results)) {
				array_shift($results);
				return $returnParts ? $results : true;
			}
		}

		return $this->getPostId();
	}
	
	/**
	 * Retrieve an associative array of token => value for loading a post by it's permalink
	 *
	 * @param string $uri
	 * @return array
	 */
	protected function _getTokenValueArray($uri)
	{
		if (($tokens = $this->_getPermalinkTokens()) && ($values = $this->isPostUri($uri, true))) {
			if (count($tokens) == count($values)) {
				$loadValues = array();
				
				foreach($tokens as $i => $token) {
					$loadValues[$token] = $values[$i];
				}
				
				return $loadValues;
			}
		}
		
		return false;
	}
	
	/**
	 * Loads a post model based on the URI (array)
	 *
	 * @param string|array $explodedUri
	 * @return FIshpig_Wordpress_Model_Post
	 */	
	public function loadByPermalink($uri)
	{
		if ($this->useGuidLinks()) {
			return Mage::getModel('wordpress/post')->load($this->getPostId());
		}

		if ($loadTokens = $this->_getTokenValueArray($uri)) {

			$posts 	= Mage::getResourceModel('wordpress/post_collection')->addIsPublishedFilter();
			$date 	= array();
			$time 	= array();
			
			foreach($loadTokens as $token => $value) {
				switch($token) {
					case 'year': 		$date[0]	= $value;										break;
					case 'monthnum':	$date[1] 	= $value;										break;
					case 'day':			$date[2] 	= $value;										break;
					case 'hour':		$time[0] 	= $value;										break;
					case 'minute':		$time[1] 	= $value;										break;
					case 'second':		$time[2] 	= $value;										break;
					case 'post_id':		$posts->addFieldToFilter('ID', $value);						break;
					case 'postname':	$posts->addFieldToFilter('post_name', strtolower(urlencode($value)));	break;
					case 'category':	$posts->addCategorySlugFilter($value);						break;
					case 'author':																	break;
				}
			}

			if ($dateStr = $this->_craftDateString($date, $time)) {
				$posts->addPostDateFilter($dateStr);
			}
			
			$posts->setCurPage(1)->setPageSize(1)->load();

			if (count($posts) == 1) {
				return $posts->getFirstItem();
			}
		}

		return false;
	}	

    /**
     * return the  permalink based on permalink structure
     * which is defined in WP Admin
     *
     * @param Fishpig_Wordpress_Model_Post
     * @return string
     */
	public function getPermalink(Fishpig_Wordpress_Model_Post $post)
	{
		if ($this->useGuidLinks()) {
			return $this->getUrl('?p='.$post->getId());
		}
		else {
			$structure = $this->_getExplodedPermalinkStructure();

			if (count($structure) > 0) {
				$url = array();

				foreach($structure as $part) {
					if (preg_match('/^\%[a-zA-Z0-9_]{1,}\%$/', $part)) {
						$part = trim($part, '%');
					
						switch($part) {
							case 'year': 		$url[] 	= $post->getPostDate('Y');		break;
							case 'monthnum':	$url[] 	= $post->getPostDate('m');		break;
							case 'day':			$url[]  = $post->getPostDate('d');		break;
							case 'hour':		$url[]  = $post->getPostDate('H');		break;
							case 'minute':		$url[]  = $post->getPostDate('i');		break;
							case 'second':		$url[]  = $post->getPostDate('s');		break;
							case 'post_id':		$url[] 	= $post->getId();				break;
							case 'postname':	$url[] 	= urldecode($post->getPostName());			break;
							case 'category':	$url[] 	= $this->_getPermalinkCategoryPortion($post);	break;
							case 'author':																break;
							default:			$this->log("Unknown permalink token ({$segment})");		break;
						}
					}
					else {
						$url[] = $part;
					}
				}

				return $this->getUrl(implode('', $url));
			}
		}
	}
	
	/**
	 * Craft a date string to help load posts
	 *
	 * @param array $date = array
	 * @param array $time = array
	 * @return string
	 */
	protected function _craftDateString(array $date = array(), array $time = array())
	{
		$dateStr = '';

		foreach(array('-' => $date, ':' => $time) as $sep => $values) {
			for($i = 0; $i <= 2; $i++ ) {
				$dateStr .= (isset($values[$i]) ? $values[$i] : '%') . $sep;
			}
			
			$dateStr = rtrim($dateStr, $sep) . ' ';
		}
		
		return rtrim($dateStr);
	}

	/**
	 * Generates the category portion of the URL for a post
	 *
	 * @param Fishpig_Wordpress_Model_Post $post
	 * @return string
	 */
	protected function _getPermalinkCategoryPortion(Fishpig_Wordpress_Model_Post $post)
	{
		if ($category = $post->getParentCategory()) {
			return trim($category->getSlug(), '/');
		}
	}
	
	/**
	 * Retrieve the post ID from the query string
	 *
	 * @return string
	 */
	public function getPostId()
	{
		return Mage::app()->getRequest()->getParam($this->getPostIdVar());
	}
	
	/**
	 * Retrieve the variable used for post ID's when using Guid links
	 *
	 * @return string
	 */
	public function getPostIdVar()
	{
		return $this->_postIdVar;
	}
	
	/**
	 * Retrieve the URL for the tags page
	 *
	 * @return string
	 */
	public function getTagsUrl()
	{
		return $this->getUrl('tags');
	}

	/**
	 * Retrieve the number of comments to display per page
	 *
	 * @return int
	 */
	public function getCommentsPerPage()
	{
		return $this->getCachedWpOption('page_comments') ? Mage::helper('wordpress')->getCachedWpOption('comments_per_page', 50) : 0;
	}
}
