<?php
/**
 * Product category helper.
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
class Blueknow_Recommender_Helper_Category extends Mage_Core_Helper_Abstract {
	
	/**
	 * Return current category path or get it from current category and creating array of categories paths.
	 * @return array Items defined as ('id', 'name') pair.
	 */
	public function getCategoryPath() {
		$path = array();
		if ($category = Mage::registry('current_category')) {
			$pathInStore = $category->getPathInStore();
			$pathIds = array_reverse(explode(',', $pathInStore));
			$categories = $category->getParentCategories();
			//add category path
			foreach ($pathIds as $categoryId) {
				if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
					$path['category' . $categoryId] = array(
						'id' => $categoryId,
						'name' => addslashes($categories[$categoryId]->getName())
					);
				}
			}
		}
		return $path;
	}
}