<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Block_Search_Form extends Mage_Core_Block_Template
{
	/**
	 * Retrieve the action URL for the search form
	 *
	 * @return string
	 */
	public function getFormActionUrl()
	{
		return $this->helper('wordpress')->getUrl($this->helper('wordpress/search')->getSearchRoute()) . '/';
	}
}
