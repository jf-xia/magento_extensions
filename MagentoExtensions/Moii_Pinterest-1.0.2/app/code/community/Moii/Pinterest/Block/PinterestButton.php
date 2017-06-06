<?php
 
class Moii_Pinterest_Block_PinterestButton extends Mage_Core_Block_Template
{
	
	public $product;
	public $productPrice;
	public $url;
	public $media;
	public $desc;
	public $count;

    /**
     * Constructor. Set template.
     */
    protected function _construct()
    {
        if (Mage::getStoreConfig('Moii_Pinterest_Config/configuration/Moii_Pinterest_Enabled')) {
			parent::_construct();
			$this->setTemplate('moii/pinterest_button.phtml');
			$this->product = Mage::registry('current_product');
			$this->productPrice = '$'.number_format($this->product->getPrice(),2);
			$this->url = $this->helper('core/url')->getCurrentUrl();
			$this->media = $this->helper('catalog/image')->init($this->product, 'small_image')->resize(220);
			if ($this->product->getAttributeText('manufacturer') == null) {
				$this->desc = $this->helper('catalog/output')->productAttribute($this->product, $this->product->getName(), 'name');
			}
			else {
				$this->desc = $this->product->getAttributeText('manufacturer').' // '.$this->helper('catalog/output')->productAttribute($this->product, $this->product->getName(), 'name');
			}
			if (Mage::getStoreConfig('Moii_Pinterest_Config/configuration/Moii_Pinterest_Price') != 0) {
				if (Mage::getStoreConfig('Moii_Pinterest_Config/configuration/Moii_Pinterest_Price') == 1) {
					$this->desc .= ' - '.$this->productPrice;
				}
				if ((Mage::getStoreConfig('Moii_Pinterest_Config/configuration/Moii_Pinterest_Price') == 2) && ($this->product->special_price != 0)) {
					$this->desc .= ' - $'.number_format($this->product->special_price,2);
				}				
			}
			$this->count = Mage::getStoreConfig('Moii_Pinterest_Config/configuration/Moii_Pinterest_Count');
		}
    }
	
	protected function getProduct() {
		return $this->product;
	}
	
	protected function getProductPrice() {
		return $this->productPrice;
	}
	
	protected function getPUrl() {
		return $this->url;
	}	
	
	protected function getMedia() {
		return $this->media;
	}
	
	protected function getDesc() {
		return $this->desc;
	}
	
	protected function getCount() {
		return $this->count;
	}
}
?>