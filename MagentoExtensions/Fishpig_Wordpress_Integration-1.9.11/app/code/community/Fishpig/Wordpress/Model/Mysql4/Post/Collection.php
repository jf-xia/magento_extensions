<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Model_Mysql4_Post_Collection extends Fishpig_Wordpress_Model_Mysql4_Post_Collection_Abstract
{
	/**
	 * True if term tables have been joined
	 * This stops the term tables being joined repeatedly
	 *
	 * @var array()
	 */
	protected $_termTablesJoined = array();
	
	public function _construct()
	{
		$this->_init('wordpress/post');
	}

	/**
	 * Filters the collection by an array of post ID's and category ID's
	 * When filtering by a category ID, all posts from that category will be returned
	 * If you change the param $operator to AND, only posts that are in a category specified in
	 * $categoryIds and $postIds will be returned
	 *
	 * @param mixed $postIds
	 * @param mixed $categoryIds
	 * @param string $operator
	 */
	public function addCategoryAndPostIdFilter($postIds, $categoryIds, $operator = 'OR')
	{
		if (!is_array($postIds)) {
			$postIds = array($postIds);
		}
		
		if (!is_array($categoryIds)) {
			$categoryIds = array($categoryIds);
		}
		
		$postSql = Mage::helper('wordpress/db')->getWordpressRead()->quoteInto("`main_table`.`ID` IN (?)", $postIds);
		$categorySql = Mage::helper('wordpress/db')->getWordpressRead()->quoteInto("`tax`.`term_id` IN (?)", $categoryIds);
		
		$this->joinTermTables('category');
		
		if (count($postIds) > 0 && count($categoryIds) > 0) {
			$this->getSelect()->where("{$postSql} {$operator} {$categorySql}");
		}
		else if (count($postIds) > 0) {
			$this->getSelect()->where("{$postSql}");
		}
		else if (count($categoryIds) > 0) {
			$this->getSelect()->where("{$categorySql}");	
		}

		return $this;	
	}
	
	/**
	 * Filters the collection by a term ID and type
	 *
	 * @param int|array $termId
	 * @param string $type
	 */
	public function addTermIdFilter($termId, $type)
	{
		$this->joinTermTables($type);
		
		if (is_array($termId)) {
			$this->getSelect()->where("`tax`.`term_id` IN (?)", $termId);
		}
		else {
			$this->getSelect()->where("`tax`.`term_id` = ?", $termId);
		}

		return $this;
	}

	/**
	 * Joins the category tables to the collection
	 * This allows filtering by category
	 */
	public function joinTermTables($type)
	{
		if (!isset($this->_termTablesJoined[$type])) {
			$tableTax = Mage::helper('wordpress/db')->getTableName('term_taxonomy');
			$tableTermRel	 = Mage::helper('wordpress/db')->getTableName('term_relationships');
			$tableTerms = Mage::helper('wordpress/db')->getTableName('terms');
			
			$this->getSelect()->join(array('rel' => $tableTermRel), "`rel`.`object_id`=`main_table`.`ID`", '');
			$this->getSelect()->join(array('tax' => $tableTax), "`tax`.`term_taxonomy_id`=`rel`.`term_taxonomy_id` AND `tax`.`taxonomy`='{$type}'", '');
			$this->getSelect()->join(array('terms' => $tableTerms), "`terms`.`term_id` = `tax`.`term_id`", '');
			$this->getSelect()->distinct();
			
			$this->_termTablesJoined[$type] = true;
		}

		return $this;
	}

	/**
	 * Filters the collection by a category slug
	 *
	 * @param string $categorySlug
	 */
	public function addCategorySlugFilter($categorySlug)
	{
		return $this->joinTermTables('category')
			->addFieldToFilter('slug', $categorySlug);
	}

	/**
	  * Filter the collection by a category ID
	  *
	  * @param int $categoryId
	  * @return $this
	  */
	public function addCategoryIdFilter($categoryId)
	{
		return $this->addTermIdFilter($categoryId, 'category');
	}
	
	/**
	  * Filter the collection by a tag ID
	  *
	  * @param int $categoryId
	  * @return $this
	  */
	public function addTagIdFilter($tagId)
	{
		return $this->addTermIdFilter($tagId, 'post_tag');
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
	
	/**
	 * Filters the collection with an archive date
	 * EG: 2010/10
	 *
	 * @param string $archiveDate
	 */
	public function addArchiveDateFilter($archiveDate, $isDaily = false)
	{
		if ($isDaily) {
			$this->getSelect()->where("`main_table`.`post_date` LIKE ?", str_replace("/", "-", $archiveDate)." %");
		}
		else {
			$this->getSelect()->where("`main_table`.`post_date` LIKE ?", str_replace("/", "-", $archiveDate)."-%");
		}
			
		return $this;	
	}
	
	public function addPostDateFilter($dateStr)
	{
		if (strpos($dateStr, '%') !== false) {
			$this->addFieldToFilter('post_date', array('like' => $dateStr));
		}
		else {
			$this->addFieldToFilter('post_date', $dateStr);
		}
		
		return $this;
	}
	
	/**
	 * Filters the collection by an array of words on the array of fields
	 *
	 * @param array $words - words to search for
	 * @param array $fields - fields to search
	 * @param string $operator
	 */
	public function addSearchStringFilter(array $words, array $fields, $operator)
	{
		if (count($words) > 0) {
			$read = Mage::helper('wordpress/db')->getWordpressRead();
			$where = array();
	
			foreach($fields as $field) {
				foreach($words as $word) {
					$where[] = $read->quoteInto("{$field} LIKE ? ", "%{$word}%");
				}
			}
	
			$this->getSelect()->where(implode(" {$operator} ", $where));
		}
		else {
			$this->getSelect()->where('1=2');
		}
		
		return $this;
	}
	
	protected function _afterLoad()
	{
		if (Mage::getDesign()->getArea() == 'adminhtml') {
			if ($product = Mage::registry('product')) {
				foreach($this as $item) {
					$item->setPositionInProduct($item->getResource()->getPositionInProduct($item, $product->getId()));
				}
			}
		}
	
		return parent::_afterLoad();
	}
}

