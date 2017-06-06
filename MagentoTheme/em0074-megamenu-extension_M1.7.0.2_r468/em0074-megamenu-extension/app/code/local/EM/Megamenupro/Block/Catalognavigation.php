<?php
class EM_Megamenupro_Block_Catalognavigation extends Mage_Catalog_Block_Navigation {
	protected $_template = 'em_megamenupro/catalognavigation.phtml';

	public function renderCategoriesMenuHtml($catId = 0, $level = 0, $outermostItemClass = '', $childrenWrapClass = '') {
		if (!$catId) 
			return parent::renderCategoriesMenuHtml($level, $outermostItemClass, $childrenWrapClass);
		else {
			if (Mage::helper('catalog/category_flat')->isEnabled())
				$categories = Mage::getModel('megamenupro/megamenupro')->getCategories($catId);
			else 
				$categories = Mage::getModel('catalog/category')->getCategories($catId);

			$activeCategories = array();
			foreach ($categories as $child) {
				if ($child->getIsActive()) {
					$activeCategories[] = $child;
				}
			}
			$activeCategoriesCount = count($activeCategories);
			$hasActiveCategoriesCount = ($activeCategoriesCount > 0);

			if (!$hasActiveCategoriesCount) {
				return '';
			}

			$html = '';
			$j = 0;
			foreach ($activeCategories as $category) {
				$html .= $this->_renderCategoryMenuItemHtml(
					$category,
					$level,
					($j == $activeCategoriesCount - 1),
					($j == 0),
					true,
					$outermostItemClass,
					$childrenWrapClass,
					true
				);
				$j++;
			}

			return $html;
		}
	}	

}