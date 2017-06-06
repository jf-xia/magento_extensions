<?php

class Belvg_Facebookfree_Block_Like_Meta extends Belvg_Facebookfree_Block_Init
{

    /**
     * Get current product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Check if like option is enabled
     *
     * @return boolean
     */
    public function isActive()
    {
        return ($this->helper('facebookfree')->isActiveLike() && $this->getProduct());
    }

    /**
     * Get current product name
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->escapeHtml($this->getProduct()->getName());
    }

    /**
     * Get current product thumbnail
     *
     * @return string
     */
    public function getProductImage()
    {
        return $this->helper('catalog/image')->init($this->getProduct(), 'thumbnail');
    }

    /**
     * Get current product URL
     *
     * @return string
     */
    public function getProductUrl()
    {
        return $this->getProduct()->getProductUrl();
    }

}