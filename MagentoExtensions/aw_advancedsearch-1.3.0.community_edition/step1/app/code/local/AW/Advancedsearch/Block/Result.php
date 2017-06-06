<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedsearch
 * @version    1.3.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Advancedsearch_Block_Result extends Mage_Core_Block_Template
{
    protected $_results = null;
    protected $_listBlock = null;

    protected function _prepareLayout()
    {
        $helper = Mage::helper('awadvancedsearch');
        $queryText = Mage::app()->getRequest()->getParam('q');

        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $title = $this->__("Search results for: '%s'", $queryText);
            $breadcrumbs->addCrumb('home', array('label' => $this->__('Home'),
                'title' => $this->__('Go to Home Page'),
                'link' => Mage::getBaseUrl()))
                ->addCrumb('search', array('label' => $title,
                'title' => $title));
        }
        $title = $this->__("Search results for: '%s'", $helper->htmlEscape($queryText));
        $this->getLayout()->getBlock('head')->setTitle($title);

        return parent::_prepareLayout();
    }

    public function getProductListHtml()
    {
        return $this->getListBlock()->toHtml();
    }

    public function setListOrders()
    {
        $category = Mage::getSingleton('catalog/layer')
            ->getCurrentCategory();
        /* @var $category Mage_Catalog_Model_Category */
        $availableOrders = $category->getAvailableSortByOptions();
        unset($availableOrders['position']);
        $availableOrders = array_merge(
            array('relevance' => $this->__('Relevance')),
            $availableOrders
        );

        $catalogConfig = Mage::getSingleton('catalog/config');
        foreach ($availableOrders as $code => $title) {
            if ($title === null) {
                $attribute = $catalogConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, $code);
                if ($attribute && $attribute->getData('frontend_label')) {
                    $availableOrders[$code] = $this->__($attribute->getData('frontend_label'));
                }
            }
        }
        $this->getListBlock()
            ->setAvailableOrders($availableOrders)
            ->setDefaultDirection('asc')
            ->setSortBy('relevance');
        return $this;
    }

    public function getListBlock()
    {
        if ($this->_listBlock === null) {
            $this->_listBlock = $this->getChild('search_result_catalog');
            $this->_listBlock->addPriceBlockType('bundle', 'bundle/catalog_product_price', 'bundle/catalog/product/price.phtml');
        }
        return $this->_listBlock;
    }

    public function getResultsCount($index)
    {
        if ($index->getData('type') == AW_Advancedsearch_Model_Source_Catalogindexes_Types::CATALOG) {
            if (method_exists($this->getListBlock(), 'getLayer')) {
                $layer = $this->getListBlock()->getLayer();
            } elseif (Mage::registry('current_layer')) {
                $layer = Mage::registry('current_layer');
            } else {
                $layer = Mage::getSingleton('catalog/layer');
            }
            $collection = $layer->getProductCollection();
            $size = count($collection->getData());
            $collection->setSizeTo($size);
            return $size;
        } else {
            return $index->getResultsCount();
        }
    }

    public function getNoResultText()
    {
        if (Mage::helper('catalogsearch')->isMinQueryLength()) {
            return Mage::helper('catalogsearch')->__('Minimum Search query length is %s', Mage::helper('catalogsearch')->getMinQueryLength());
        }
        return $this->__('There is no items matching the selection');
    }

    public function getResults($type = null)
    {
        if ($this->_results === null) {
            $this->_results = Mage::helper('awadvancedsearch/catalogsearch')->getResults();
        }
        if ($type) {
            foreach ($this->_results as $index) {
                if ($index->getData('type') == $type) {
                    return $index;
                }
            }
            return null;
        }
        return $this->_results;
    }

    public function getTypeLabel($type)
    {
        switch ($type) {
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CATALOG:
                $label = 'Catalog';
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CMS_PAGES:
                $label = 'Other Results';
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_BLOG:
                $label = 'Blog';
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_KBASE:
                $label = 'Kbase';
                break;
            default:
                $label = '';
        }
        return $this->__($label);
    }

    public function getHasResults()
    {
        return !($this->getFirstMatchedType() === null);
    }

    public function getFirstMatchedType()
    {
        if (is_array($this->getResults()) || $this->getResults() instanceof Traversable) {
            foreach ($this->getResults() as $index) {
                if ($index->getResultsCount()) {
                    return $index->getData('type');
                }
            }
        }
        return null;
    }

    public function getCurrentType()
    {
        $currentType = $this->getRequest()->getParam('type');
        if ($currentType === null || $this->getResults($currentType) === null) {
            $currentType = $this->getFirstMatchedType();
        }
        return $currentType;
    }

    public function getAWBlogResultsHtml()
    {
        $blogBlock = $this->getChild('search_result_awblog');
        $blogBlock->setData('index', $this->getResults(AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_BLOG));
        return $blogBlock->toHtml();
    }

    public function getAWKbaseResultsHtml()
    {
        $blogBlock = $this->getChild('search_result_awkbase');
        $blogBlock->setData('index', $this->getResults(AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_KBASE));
        return $blogBlock->toHtml();
    }

    public function getCMSPagesResultsHtml()
    {
        $cmsPagesBlock = $this->getChild('search_result_cms_pages');
        $cmsPagesBlock->setData('index', $this->getResults(AW_Advancedsearch_Model_Source_Catalogindexes_Types::CMS_PAGES));
        return $cmsPagesBlock->toHtml();
    }

    public function getContent()
    {
        switch ($this->getCurrentType()) {
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CATALOG:
                return $this->getProductListHtml();
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CMS_PAGES:
                return $this->getCMSPagesResultsHtml();
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_BLOG:
                return $this->getAWBlogResultsHtml();
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_KBASE:
                if (!Mage::helper('awadvancedsearch')->canUseAWKBase()) {
                    break;
                }
                return $this->getAWKbaseResultsHtml();
                break;
        }
    }

    public function getMatchedIds($index)
    {
        return Mage::helper('awadvancedsearch/results')->getMatchedIds($index);
    }
}
