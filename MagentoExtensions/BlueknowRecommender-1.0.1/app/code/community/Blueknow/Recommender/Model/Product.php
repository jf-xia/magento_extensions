<?php
/**
 * Product object model.
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
class Blueknow_Recommender_Model_Product extends Varien_Object {
	
	const DATA_PRODUCT = 'current_product';
	
	/**
	 * Determine if current product is available.
	 * @var bool
	 */
	private $_hasProduct;
	
	/**
	 * Product identifier.
	 * @var string
	 */
	private $_id;
	
	/**
	 * Product name.
	 * @var string
	 */
	private $_name;
	
	/**
	 * Product single-line description.
	 * @var string
	 */
	private $_description;
	
	/**
	 * Product absolute URL.
	 * @var string
	 */
	private $_url;
	
	/**
	 * Product image absolute URL.
	 * @var string
	 */
	private $_imageUrl;
	
	/**
	 * Product price including taxes and currency conversion. No currency symbol.
	 * @var number|string
	 */
	private $_price;
	
	/**
	 * Determine if product is in stock (saleable).
	 * @var bool
	 */
	private $_inStock;
	
	/**
	 * Categories (and subcategories) where product is placed.
	 * @var array of pairs ('id', 'name')
	 */
	private $_categories;
	
	public function __construct() {
		parent::__construct();
		$this->_getProduct(); //loading of current product
		$this->_hasProduct = $this->hasData(self::DATA_PRODUCT) && $this->getData(self::DATA_PRODUCT) instanceof Mage_Catalog_Model_Product;
	}
	
	/**
	 * Get current product identifier.
	 * @return string
	 */
	public function getIdentifier() {
		if (empty($this->_id) && $this->_hasProduct) {
			$this->_id = $this->_getProduct()->getId();
		}
		return $this->_id;
	}
	
	/**
	 * Get current product name.
	 * @return string
	 */
	public function getName() {
		if (empty($this->_name) && $this->_hasProduct) {
			//[2011-03-14] Issue MAGPLUGIN-4: Quotes in product name causes a JavaScript error.
			//			   Calls to trim() and addslashes() functions have been added.
			$this->_name = trim(addslashes($this->_getProduct()->getName()));
		}
		return $this->_name;
	}
	
	/**
	 * Get current product description (single line, no HTML tags).
	 * @return string
	 */
	public function getDescription() {
		if (empty($this->_description) && $this->_hasProduct) {
			$this->_description = Mage::helper('blueknow_recommender')->ssline($this->_getProduct()->getDescription());
		}
		return $this->_description;
	}
	
	/**
	 * Get current product absolute URL.
	 * @return string
	 */
	public function getUrl() {
		if (empty($this->_url) && $this->_hasProduct) {
			$this->_url = $this->_getProduct()->getProductUrl();
		}
		return $this->_url;
	}
	
	/**
	 * Get current product image absolute URL.
	 * @return string
	 */
	public function getImageUrl() {
		if (empty($this->_imageUrl) && $this->_hasProduct) {
			//[2011-03-14] Issue MAGPLUGIN-1: Track cached images to improve loading time of the widget.
			//$this->_imageUrl = Mage::helper('catalog/product')->getSmallImageUrl($this->_getProduct()); //it internally deals with no-image products
			$this->_imageUrl = Mage::helper('catalog/image')->init($this->_getProduct(), 'small_image');
		}
		return $this->_imageUrl;
	}
	
	/**
	 * Get current product price, within currency conversion applied and including taxes. No currency symbol.
	 * return number|string
	 */
	public function getPrice() {
		if (empty($this->_price) && $this->_hasProduct) {
			$this->_price = Mage::helper('blueknow_recommender/Price')->getFinalPrice($this->_getProduct());
		}
		return $this->_price;
	}
	
	/**
	 * Determine if current product is saleable (in stock).
	 * @return bool
	 */
	public function isSaleable() {
		if (empty($this->_inStock) && $this->_hasProduct) {
			$this->_inStock = $this->_getProduct()->isSaleable();
		}
		return $this->_inStock;
	}
	
	/**
	 * Get categories (and subcategories) where current product is placed. Every category is defined by the pair (id, name).
	 * @return array
	 */
	public function getCategories() {
		if (empty($this->_categories)) {
			$this->_categories = Mage::helper('blueknow_recommender/category')->getCategoryPath();
		}
		return $this->_categories;
	}
	
	/**
	 * Get current Product.
	 * @return Mage_Catalog_Model_Product
	 */
	protected function _getProduct() {
		if (!$this->hasData(self::DATA_PRODUCT) || !$this->getData(self::DATA_PRODUCT) instanceof Mage_Catalog_Model_Product) {
			$this->setData(self::DATA_PRODUCT, Mage::helper('catalog')->getProduct());
		}
		return $this->getData(self::DATA_PRODUCT);
	}
}