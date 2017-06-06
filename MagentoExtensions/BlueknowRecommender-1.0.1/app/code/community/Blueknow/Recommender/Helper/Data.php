<?php
/**
 * Base helper.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Blueknow Recommender
 * extension to newer versions in the future. If you wish to customize it for
 * your needs please save your changes before upgrading.
 * 
 * @category	Blueknow
 * @package		Blueknow_Recommender
 * @copyright	Copyright (c) 2010 Blueknow, S.L. (http://www.blueknow.com)
 * @license		GNU General Public License
 * @author		<a href="mailto:santi.ameller@blueknow.com">Santiago Ameller</a>
 * @since		1.0.0
 * 
 */
class Blueknow_Recommender_Helper_Data extends Mage_Core_Helper_Abstract {

	/**
	 * Default number of characters for truncation.
	 * @var int
	 */
	const DEFAULT_TRUNCATION_LENGTH = 255;
	
	/**
	 * Default character sequence to use at the end of the truncation.
	 * @var string
	 */
	const DEFAULT_TRUNCATION_ETC = '...';
	
	/**
	 * Single-simple-line transformation: no multiple line, no embedded HTML code. Also a truncation is applied.
	 * @param string $string
	 */
	public function ssline($string) {
		$string_helper = Mage::helper('core/string');
		$string = trim(addslashes(preg_replace("#(\r\n|\n|\r)#s", ' ', strip_tags($string))));
		$reminder = '';
		return $string_helper->truncate($string, self::DEFAULT_TRUNCATION_LENGTH, self::DEFAULT_TRUNCATION_ETC, $reminder, FALSE);
	}
}