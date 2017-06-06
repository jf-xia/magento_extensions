<?php
/**
 * Config Number of recommedantions source.
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
class Blueknow_Recommender_Model_Source_Numberofrecommendations {
	
	/**
	 * Get options for number of recommendations selector. It defines the rang 3-20.
	 * @return array
	 */
	public function toOptionArray() {
		for ($i=3; $i<=20; $i++) {
			$options[$i] = array('value' => $i, 'label' => $i);
		}
		return $options;
	}
}