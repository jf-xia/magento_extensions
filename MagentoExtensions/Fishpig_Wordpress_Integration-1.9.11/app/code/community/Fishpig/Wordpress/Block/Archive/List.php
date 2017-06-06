<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Block_Archive_List extends Mage_Core_Block_Template
{
	/**
	 * Cache for archive collection
	 *
	 * @var null|Varien_Data_Collection
	 */
	protected $_archiveCollection = null;

	/**
	 * Returns a collection of valid archive dates
	 *
	 * @return Varien_Data_Collection
	 */
	public function getArchives()
	{
		if (is_null($this->_archiveCollection)) {
			$table = Mage::helper('wordpress/db')->getTableName('posts');
			$sql = "SELECT COUNT(ID) AS post_count, CONCAT(SUBSTRING(post_date, 1, 4), '/', SUBSTRING(post_date, 6, 2)) as archive_date 
					FROM `" . $table . "` AS `main_table` WHERE (`main_table`.`post_type`='post') AND (`main_table`.`post_status` ='publish') 
					GROUP BY archive_date ORDER BY archive_date DESC";
					
			$dates = Mage::helper('wordpress/db')->getWordpressRead()->fetchAll($sql);
			$collection  = new Varien_Data_Collection();
			
			foreach($dates as $date) {
				$obj = Mage::getModel('wordpress/archive')->load($date['archive_date']);
				$obj->setPostCount($date['post_count']);
				$collection->addItem($obj);
			}

			$this->_archiveCollection = $collection;
		}
		
		return $this->_archiveCollection;
	}
	
	/**
	 * Split a date by spaces and translate
	 *
	 * @param string $date
	 * @param string $splitter = ' '
	 * @return string
	 */
	public function translateDate($date, $splitter = ' ')
	{
		$dates = explode($splitter, $date);
		
		foreach($dates as $it => $part) {
			$dates[$it] = $this->__($part);
		}
		
		return implode($splitter, $dates);
	}
}
