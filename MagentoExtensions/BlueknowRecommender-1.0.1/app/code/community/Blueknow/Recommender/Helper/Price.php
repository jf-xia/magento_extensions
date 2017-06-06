<?php
/**
 * Product price helper.
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
class Blueknow_Recommender_Helper_Price extends Mage_Core_Helper_Abstract {
	
	/**
	 * Get final price of a given product within currency conversion.
	 * @param Mage_Catalog_Model_Product $product
	 */
	//[2011-03-14] Issue MAGPLUGIN-2: Grouped, configurable and bundled products are not rendered when price is retrieved.
	//			   Price retrieval strategy changed using the product price model.
	public function getFinalPrice($product) {
		//get price according to product type and price model
		if (isset($product) && $product instanceof Mage_Catalog_Model_Product) {
			//get product price model
			$model = $product->getPriceModel();
			//get product price
			$price = $model->getFinalPrice(1, $product);
			if ($price == 0) {
				$type = $product->getTypeId();
				switch ($type) {
					case Mage_Catalog_Model_Product_Type::TYPE_BUNDLE:
						$price = $this->getBundledProductMinimalPrice($product);
						break;
					case Mage_Catalog_Model_Product_Type::TYPE_GROUPED:
						$price = $this->getGroupedProductMinimalPrice($product);
						break;
					default:
						$price = -1; //invalid price
				}
			}
			//apply currency conversion
			$price = Mage::helper('core')->currency($price, false, false);
		} else {
			$price = -1; //invalid price
		}
		return $price;
	}
	
	/**
	 * Get bundled product price taking it minimal price.
	 * @param Mage_Catalog_Model_Product $product
	 * @return price
	 * @since 1.0.1.GA (see issue MAGPLUGIN-2)
	 */
	protected function getBundledProductMinimalPrice($product) {
		$price = $product->getMinimalPrice();
		$price = $price && $price > 0 ? $price : Mage::getModel('bundle/Product_Price')->getPrices($product, 'min');
		return $price;
	}
	
	/**
	 * Get grouped product price as a sum of associated products.
	 * @param Mage_Catalog_Model_Product $groupedProduct
	 * @return price
	 * @since 1.0.1.GA (see issue MAGPLUGIN-2)
	 */
	protected function getGroupedProductMinimalPrice($groupedProduct) {
		$aProductIds = $groupedProduct->getTypeInstance()->getChildrenIds($groupedProduct->getId());
		$prices = array();
		foreach ($aProductIds as $ids) {
			foreach ($ids as $id) {
				$aProduct = Mage::getModel('catalog/product')->load($id);
				$prices[] = $aProduct->getPriceModel()->getFinalPrice(null, $aProduct, true);
			}
        }
        asort($prices);
        return array_shift($prices);
	}
}