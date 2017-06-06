<?php
/**
 * Luxe
 * Bestsellers module
 *
 * @category   Luxe 
 * @package    Luxe_Bestsellers
 */


/**
 * Product list
 *
 * @category   Luxe
 * @package    Luxe_Bestsellers
 * @author     Yuriy V. Vasiyarov
 */
class Luxe_Bestsellers_Block_List extends Mage_Catalog_Block_Product_List 
{
    protected $_defaultToolbarBlock = 'bestsellers/list_toolbar';

    public function _toHtml()
    {
        if ($this->_productCollection->count()) {
            return parent::_toHtml();
        } else {
            return '';
        }
    } 

    public function getTimeLimit() 
    { 
        if ($this->getData('time_limit_in_days')) {
            return intval($this->getData('time_limit_in_days'));
        } else {
            return intval(Mage::getStoreConfig('bestsellers/bestsellers/time_limit_in_days'));
        }
    }

    public function getProductsLimit() 
    { 
        if ($this->getData('limit')) {
            return intval($this->getData('limit'));
        } else {
            return $this->getToolbarBlock()->getLimit(); 
        }
    }

    public function getDisplayMode() 
    { 
        return $this->getData('display_mode');
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        $storeId = Mage::app()->getStore()->getStoreId();
        $this->setStoreId($storeId);
        if (is_null($this->_productCollection)) {
            $this->_productCollection = Mage::getResourceModel('reports/product_collection');
            if ($this->getTimeLimit()) {
                $product = Mage::getModel('catalog/product');
                $todayDate = $product->getResource()->formatDate(time());
                $startDate = $product->getResource()->formatDate(time() - 60 * 60 * 24 * $this->getTimeLimit());
                $this->_productCollection = $this->_productCollection->addOrderedQty($startDate, $todayDate);
            } else {
                $this->_productCollection = $this->_productCollection->addOrderedQty();
            }
            $this->_productCollection = $this->_productCollection->addAttributeToSelect('*')
                            ->setStoreId($storeId)
                            ->addStoreFilter($storeId)
                            ->setOrder('ordered_qty', 'desc')
                            ->setPageSize($this->getProductsLimit());

            $checkedProducts = new Varien_Data_Collection();
            $curPage = 1;
            while (count($checkedProducts) < $this->getProductsLimit()) {
                $this->_productCollection->clear()->setCurPage($curPage)->load();

                if ($this->_productCollection->getCurPage() != $curPage) {
                    break; //if bestsellers list is over simply exit
                }
                foreach ($this->_productCollection as $k => $p) {
                    $p = $p->loadParentProductIds();
                    $parentIds = $p->getData('parent_product_ids');
                    // if product is part of configurable product get first parent product
                    if (is_array($parentIds) && !empty($parentIds)) {
                        if (!$checkedProducts->getItemById($parentIds[0])) {
                            $parentProduct = Mage::getModel('catalog/product')->setStoreId($storeId)->load($parentIds[0]);
                            if ($parentProduct->isVisibleInCatalog()) {
                                $checkedProducts->addItem($parentProduct);
                            }
                        }
                    } else {
                        if (!$checkedProducts->getItemById($k)) {
                            $checkedProducts->addItem($p);
                        }
                    }
                    if (count($checkedProducts) >= $this->getProductsLimit()) {
                        break;
                    }
                }
                $curPage++;
            }
            $this->_productCollection = $checkedProducts;
        }
        return $this->_productCollection;
    }

    /**
     * Translate block sentence
     *
     * @return string
     */
    public function __()
    {
        $args = func_get_args();
        $expr = new Mage_Core_Model_Translate_Expr(array_shift($args), 'Mage_Catalog');
        array_unshift($args, $expr);
        return Mage::app()->getTranslator()->translate($args);
    }

}
