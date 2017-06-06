<?php
class Magestore_Groupdeal_Block_Deal extends Mage_Core_Block_Template
{	
	const WAITING 	= 6;
	const OPENING 	= 5;
	const REACHED 	= 4;
	const UNREACHED	= 3;
	const END 		= 2;
	const ENABLED 	= 1;
	const DISABLED	= 0;
	
	
    protected $_priceBlock = array();
    protected $_priceBlockDefaultTemplate = 'catalog/product/price.phtml';
    protected $_tierPriceDefaultTemplate  = 'catalog/product/view/tierprices.phtml';
    protected $_priceBlockTypes = array();	
	protected $_useLinkForAsLowAs = true;
	protected $_reviewsHelperBlock;
	
	public function _prepareLayout(){
		$deal = $this->getDeal();		
		$headBlock = $this->getLayout()->getBlock('head');
		$headBlock->setTitle($deal->getDealTitle());
		return parent::_prepareLayout();
    }
	
	public function getDeal(){
		$dealId = $this->getRequest()->getParam('id');		
		$deal = Mage::getModel('groupdeal/deal')->load($dealId);
		return $deal;
	}
	
	public function getProduct(){
		$deal = $this->getDeal();
		return Mage::getModel('catalog/product')->load($deal->getProductId());
	}
	
	public function getImages(){
		$dealId = $this->getRequest()->getParam('id');
		$images = Mage::getModel('groupdeal/image')->getCollection()
				->addFieldToFilter('deal_id', $dealId)
				->setOrder('sort_order', 'ASC');
		return $images;
	}
	
	public function getAddToCartUrl(){
		$deal = $this->getDeal();
		return $this->getUrl('checkout/cart/add', array('product'=>$deal->getProductId()));
    }
	
	public function getWhatHappenUrl(){
		return $this->getUrl('groupdeal/index/whatHappenToYourPurchase');
	}
	
	public function getWhatHappen(){
		return Mage::getStoreConfig('groupdeal/general/what_happen');
	}
		
	public function getProductIdsInDeal(){		
		$dealId = $this->getRequest()->getParam('id');
		$collection = Mage::getModel('groupdeal/productlist')->getCollection()
					->addFieldToFilter('deal_id', $dealId);
		$productIds = array(); 
		foreach($collection as $item){
			$productIds[] = $item->getProductId();
		}
		return $productIds;
	}
	
	public function getProductsInDeal(){
		$productIds = $this->getProductIdsInDeal();				
		$products = array();
		$collection = Mage::getModel('catalog/product')->getCollection()
					->addFieldToFilter('entity_id', array('in'=>$productIds))
					->addAttributeToSelect('*')
					->addAttributeToSort('price', 'DESC');
		if(count($collection)){
			foreach($collection as $item){				
				$products[] = $item;
			}
		}
		
		return $products;
	}
	
	
	public function getGalleryImages(){
        $productIds = $this->getProductIdsInDeal();
		$images = array();
		
		foreach($productIds as $productId){
			$product = Mage::getModel('catalog/product')->load($productId);
			$galleryImages = $product->getMediaGalleryImages();
			foreach($galleryImages as $galleryImage){
				$images[] = array('image' => $galleryImage, 'product' => $productId);
			}
		}
		return $images;
    }
	
	public function getGalleryUrl($image, $productId){	
        $params = array('id'=>$productId);
        if ($image) {
            $params['image'] = $image->getValueId();
            return $this->getUrl('catalog/product/gallery', $params);
        }
        return $this->getUrl('catalog/product/gallery', $params);
    }
	
	public function isShowProductList(){
		return Mage::getStoreConfig('groupdeal/show_productlist/is_show');
	}
	
	public function getProductListTitle(){
		return Mage::getStoreConfig('groupdeal/show_productlist/title');
	}
	
	public function getProductListDescription(){
		return Mage::getStoreConfig('groupdeal/show_productlist/description');
	}
	
	public function getCategory(){
		$dealId = $this->getRequest()->getParam('id');
		
		$collection = Mage::getModel('groupdeal/productlist')->getCollection()
				->addFieldToFilter('deal_id', $dealId);
		
		$productIds = array();
		if(count($collection)) {
			foreach($collection as $item) {
				$productIds[] = $item->getProductId();
			}
		}
		
		$categoryIds = array();
		foreach($productIds as $productId){
			$product = Mage::getModel('catalog/product')->load($productId);
			$categoryIds = array_merge($product->getCategoryIds(), $categoryIds);
		}
		
		$category = Mage::getModel('catalog/category')->getCollection()
					->addFieldToFilter('entity_id', array('in'=>$categoryIds))
					->addAttributeToSelect('name')
					->getFirstItem();

		return $category;
	}
	
	public function getCategoryUrl(){
		$categoryId = $this->getCategory()->getId();
		return $this->getUrl('groupdeal/index/index', array('category'=>$categoryId));
	}
	
	public function getCategoryName(){
		return $this->getCategory()->getName();
	}
	
	
    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix='')
    {
        return $this->_getPriceBlock($product->getTypeId())
            ->setTemplate($this->_getPriceBlockTemplate($product->getTypeId()))
            ->setProduct($product)
            ->setDisplayMinimalPrice($displayMinimalPrice)
            ->setIdSuffix($idSuffix)
            ->setUseLinkForAsLowAs($this->_useLinkForAsLowAs)
            ->toHtml();
    }	
	
    protected function _getPriceBlock($productTypeId)
    {
        if (!isset($this->_priceBlock[$productTypeId])) {
            $block = 'catalog/product_price';
            if (isset($this->_priceBlockTypes[$productTypeId])) {
                if ($this->_priceBlockTypes[$productTypeId]['block'] != '') {
                    $block = $this->_priceBlockTypes[$productTypeId]['block'];
                }
            }
            $this->_priceBlock[$productTypeId] = $this->getLayout()->createBlock($block);
        }
        return $this->_priceBlock[$productTypeId];
    }

    protected function _getPriceBlockTemplate($productTypeId)
    {
        if (isset($this->_priceBlockTypes[$productTypeId])) {
            if ($this->_priceBlockTypes[$productTypeId]['template'] != '') {
                return $this->_priceBlockTypes[$productTypeId]['template'];
            }
        }
        return $this->_priceBlockDefaultTemplate;
    }	
	
    public function getReviewsSummaryHtml(Mage_Catalog_Model_Product $product, $templateType = false, $displayIfNoReviews = false)
    {
        $this->_initReviewsHelperBlock();
        return $this->_reviewsHelperBlock->getSummaryHtml($product, $templateType, $displayIfNoReviews);
    }	
	
    protected function _initReviewsHelperBlock()
    {
        if (!$this->_reviewsHelperBlock) {
            $this->_reviewsHelperBlock = $this->getLayout()->createBlock('review/helper');
        }
    }	
	
	//Hai.Ta
	public function getActionUrlBuy(){
		return $this->getUrl('groupdeal/index/getProductOption');
	}
	
	public function getProductHaveOptions($_productsInDeal){
		$products = array();
		foreach ($_productsInDeal as $product){					
			if(Mage::helper('groupdeal')->checkTypeProduct($product->getTypeId())){						
				$products[] = $product;
			}
		}		
		if(count($products) == 0) return 0;		
		return $products[0];
	}
		
	// public function checkOptionProduct(){
		
	// }	
	public function getAddProductOptionToCartUrl($product, $additional = array())
    {
        if ($product->getTypeInstance(true)->hasRequiredOptions($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            if (!isset($additional['_query'])) {
                $additional['_query'] = array();
            }
            $additional['_query']['options'] = 'cart';
			
			return $product->getUrlModel()->getUrl($product, $additional);
        }
        return $this->helper('checkout/cart')->getAddUrl($product, $additional);
    }	
		
}