<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G. 
 */
class Soon_AdvancedCache_Model_Add extends Soon_AdvancedCache_Model_Abstract {

    /**
     * Active admin blocks
     * 
     * @var mixed bool|array
     */
    protected $_activeAdminBlocks;

    /**
     * Add block to cache depending on its type
     * 
     * @param object Varien_Event_Observer $observer
     * @return Soon_AdvancedCache_Model_Add
     */
    public function addBlockToCache(Varien_Event_Observer $observer) {

        $event = $observer->getEvent();
        $block = $event->getBlock();
        $class = get_class($block);

        $storeId = Mage::app()->getStore()->getId();
        $this->_storeId = $storeId;

        // If project specific cache, we use it and return
        if ($projectSpecificCache = $this->getProjectSpecificCache($block, $class)) {

            $this->addCacheInfo(
				$block,
				$projectSpecificCache->getLifetime(),
				$projectSpecificCache->getKey(),
				$projectSpecificCache->getTags());

            return $this;
        }

        // CMS page
        if ('Mage_Cms_Block_Page' == $class && $block->getPage()->getId()) {
            $lifetime = $this->getCacheConfig()->getExpire('cms');
            $key = 'cms_page_' . $block->getPage()->getId();
            $tags = array(Mage_Core_Model_Store::CACHE_TAG, Mage_Cms_Model_Page::CACHE_TAG . '_' . $block->getPage()->getId());

            // Do not cache CMS pages that are exceptions
            if (!$this->isException($block, 'cms_page')) {
                // For Homepage
                if ($this->_getRequest()->getControllerName() == 'index') {
                    $this->addHomepage($block, $storeId);
                    return $this;
                }

                $this->addCacheInfo($block, $lifetime, $key, $tags);
                return $this;
            }
        }

        // CMS block
        if ('Mage_Cms_Block_Block' == $class && $block->getBlockId()) {
            $lifetime = $this->getCacheConfig()->getExpire('cms');
            $key = 'cms_block_' . $block->getBlockId() . '_' . $storeId;
            $tags = array(Mage_Core_Model_Store::CACHE_TAG, Mage_Cms_Model_Block::CACHE_TAG . '_' . $block->getBlockId());

            $this->addCacheInfo($block, $lifetime, $key, $tags);
            return $this;
        }

        // Breadcrumbs
        if ('Mage_Page_Block_Html_Breadcrumbs' == $class) {
            $this->addBreadcrumbs($block, $storeId);
            return $this;
        }

        // Products lists
        if ('Mage_Catalog_Block_Product_List' == $class) {
            $this->addProductsList($block, $storeId);
            return $this;
        }

        // Product view
        if ('Mage_Catalog_Block_Product_View' == $class) {
            $this->addProductView($block, $storeId);
            return $this;
        }

        // Add additional admin blocks.
        // We use registry not to generate admin block cache on each block html rendering!

        $adminBlocksCacheActive = Mage::registry('admin_blocks_cache');
        if (!$adminBlocksCacheActive) {
            Mage::register('admin_blocks_cache', true);
            $this->addAdminBlocksCache();
            return $this;
        }

        // Other blocks: see Block Caching in admin
    }

    /**
     * Retrieve project specific cache
     * 
     * @param Varien_Object $block
     * @param string $class
     * @return mixed bool|Soon_AdvancedCache_Model_Project_Add
     */
    public function getProjectSpecificCache($block, $class) {
        $projectSpecificCache = Mage::getModel('advancedcache/project_add')->getCacheParameters($block, $class);
        if (!$projectSpecificCache->getFlag()) {
            return false;
        } else {
            return $projectSpecificCache;
        }
    }

    /**
     * Check if the item has been registered as an exception in the admin
     * 
     * @param Mage_Core_Block $block
     * @return bool
     */
    public function isException($block, $type) {
        $isException = false;

        $collection = Mage::getResourceModel('advancedcache/exception_collection')
                ->addFieldToFilter('item_type', $type)
                ->addFieldToFilter('item_id', $block->getPage()->getIdentifier());

        if ($collection->count()) {
            $isException = true;
        }

        return $isException;
    }

    /**
     * Add additional cache blocks from admin
     * 
     * @return Soon_AdvancedCache_Model_Add
     */
    public function addAdminBlocksCache() {
        if ($adminBlocks = $this->getAdminBlocks()) {
            foreach ($adminBlocks as $item) {
                $adminBlock = Mage::getModel('advancedcache/blocks')->load($item['block_id']);
                $block = Mage::app()->getLayout()->getBlock($adminBlock->getBlockName());

                if (is_object($block)) {
                    $specialConfiguration = $adminBlock->getSpecialConfiguration();

                    // If the block is special and needs extra cache
                    if ($specialConfiguration != '') {

                        switch ($specialConfiguration) {

                            case Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_CURRENT_CATEGORY:
                                $currentCategory = Mage::registry('current_category');
                                if ($currentCategory != '') {
                                    $lifetime = $this->getCacheConfig()->getExpire($adminBlock->getExpire());
                                    $key = $adminBlock->getIdentifier() . '_' . $currentCategory->getId() . '_' . $this->_storeId;
                                    $tags = array(Mage_Core_Model_Store::CACHE_TAG, $adminBlock->getIdentifier(), $adminBlock->getIdentifier() . '_' . $currentCategory->getId());
                                    $this->addCacheInfo($block, $lifetime, $key, $tags);
                                }
                                break;

                            case Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_CURRENT_PRODUCT:
                                $currentProduct = Mage::registry('current_product');
                                if ($currentProduct != '') {
                                    $lifetime = $this->getCacheConfig()->getExpire($adminBlock->getExpire());
                                    $key = $adminBlock->getIdentifier() . '_' . $currentProduct->getId() . '_' . $this->_storeId;
                                    $tags = array(Mage_Core_Model_Store::CACHE_TAG, $adminBlock->getIdentifier(), $adminBlock->getIdentifier() . '_' . $currentProduct->getId());
                                    $this->addCacheInfo($block, $lifetime, $key, $tags);
                                }
                                break;

                            case Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_PRODUCTS_LIST:
                                $this->addProductsList($block, $this->_storeId);
                                break;

                            case Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_PRODUCT_VIEW:
                                $this->addProductView($block, $this->_storeId);
                                break;

                            case Soon_AdvancedCache_Model_Config::SPECIAL_CONFIG_BREADCRUMBS:
                                $this->addBreadcrumbs($block, $this->_storeId);
                                break;
                        }
                    }

                    // For all other blocks which caching is standard
                    else {
                        $lifetime = $this->getCacheConfig()->getExpire($adminBlock->getExpire());
                        $key = $adminBlock->getIdentifier() . '_' . $this->_storeId;
                        $tags = array(Mage_Core_Model_Store::CACHE_TAG, $adminBlock->getIdentifier());
                        $this->addCacheInfo($block, $lifetime, $key, $tags);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Retrieve cache blocks from admin
     * 
     * @return mixed bool|array
     */
    public function getAdminBlocks() {
        if (null === $this->_activeAdminBlocks) {
            $activeAdminBlocks = Mage::getResourceModel('advancedcache/blocks_collection')->getActiveAdminBlocks();
            if ($activeAdminBlocks->count()) {
                $activeAdminBlocks = $activeAdminBlocks->toArray();
                $this->_activeAdminBlocks = $activeAdminBlocks['items'];
            } else {
                $this->_activeAdminBlocks = false;
            }
        }

        return $this->_activeAdminBlocks;
    }

    /**
     * Create cache for homepage.
     * Retrieves layout update of homepage CMS page and caches each block.
     * CAUTION : homepage layout must be defined in the "Layout Update XML" field in the "Design" tab of the CMS page edition.
     * 
     * @param object $block
     * @param int $storeId
     * @return Soon_AdvancedCache_Model_Add
     */
    public function addHomepage($block, $storeId) {
        if (Mage::app()->useCache('advancedcache_homepage')) {
            $lifetime = $this->getCacheConfig()->getShortestExpire();
            $tags = array(Mage_Core_Model_Store::CACHE_TAG, 'homepage_blocks');

            // 1. First add content field from admin
            $key = 'homepage_content_block_' . $storeId;
            $this->addCacheInfo($block, $lifetime, $key, $tags);

            // 2. Then add layout update fields in admin
            $page = Mage::getModel('cms/page')->load($block->getPage()->getId());
            $layout = Mage::app()->getLayout();
            $finalBlocks = array();

            // 2a. From layout update
            $sourceLayoutXml = '<xmlcontent>' . $page->getLayoutUpdateXml() . '</xmlcontent>';
            $layoutUpdateXml = new SimpleXMLElement($sourceLayoutXml);
            $layoutUpdateBlocks = $layoutUpdateXml->xpath("//block");

            foreach ($layoutUpdateBlocks as $block) {
                $attributes = $block->attributes();
                $finalBlocks[] = $layout->getBlock((string) $attributes->name);
            }

            // 2b. From custom layout update if in date range
            $inRange = Mage::app()->getLocale()->isStoreDateInInterval(null, $page->getCustomThemeFrom(), $page->getCustomThemeTo());
            if ($inRange) {
                $sourceCustomLayoutXml = '<xmlcontent>' . $page->getCustomLayoutUpdateXml() . '</xmlcontent>';
                $customLayoutUpdateXml = new SimpleXMLElement($sourceCustomLayoutXml);
                $customLayoutUpdateBlocks = $customLayoutUpdateXml->xpath("//block");

                foreach ($customLayoutUpdateBlocks as $block) {
                    $attributes = $block->attributes();
                    $finalBlocks[] = $layout->getBlock((string) $attributes->name);
                }
            }

            // 2c. Add cache info of all layout update fields
            if (count($finalBlocks) > 0) {
                foreach ($finalBlocks as $k => $finalBlock) {
                    $key = 'homepage_block_' . $k . '_' . $storeId;
                    $this->addCacheInfo($finalBlock, $lifetime, $key, $tags);
                }
            }
        }

        return $this;
    }

    /**
     * Create cache for breadcrumbs depending on the page type
     * 
     * @param object $block
     * @param int $storeId
     * @return Soon_AdvancedCache_Model_Add
     */
    public function addBreadcrumbs($block, $storeId) {
        $cacheBreadcrumbs = false;

        $request = $this->_getRequest();
        $controller = $request->getControllerName();

        $lifetime = $this->getCacheConfig()->getShortestExpire();
        $key = 'breadcrumbs_';

        // CMS page
        if ($controller == 'page') {
            $cacheBreadcrumbs = true;
            $key .= 'cms_page_' . $request->getParam('page_id');
        }
        // Category / products list
        if ($controller == 'category') {
            $cacheBreadcrumbs = true;
            $currentCategory = Mage::registry('current_category');
            $key .= 'category_' . $currentCategory->getId();
        }
        // Product view
        if ($controller == 'product') {
            $cacheBreadcrumbs = true;
            if (Mage::registry('current_category') != '') {
                $currentCategory = Mage::registry('current_category');
                $key .= 'category_' . $currentCategory->getId();
            }
            $currentProductId = $request->getParam('id');
            $key .= 'product_' . $currentProductId;
        }

        $key .= '_' . $storeId;

        $tags = array(Mage_Core_Model_Store::CACHE_TAG, 'breadcrumbs');

        if ($cacheBreadcrumbs) {
            $this->addCacheInfo($block, $lifetime, $key, $tags);
        }

        return $this;
    }

    /**
     * Create cache for products lists with all possible options (filters, sorting, etc.)
     * 
     * @param object $block
     * @param int $storeId
     * @return Soon_AdvancedCache_Model_Add 
     */
    public function addProductsList($block, $storeId) {
        if (Mage::registry('current_category') != '') {
            $currentCategory = Mage::registry('current_category');
            $cacheKey = 'products_list_' . $currentCategory->getId();
            if ($cacheKeys = $this->_getRequest()->getParams()) {
                foreach ($cacheKeys as $v => $p) {
                    $cacheKey .= ( $v != 'id') ? '_' . $v . '_' . $p : '';
                }
            }

            $lifetime = $this->getCacheConfig()->getExpire('catalog');
            $key = $cacheKey . '_' . $storeId;
            $tags = array(Mage_Core_Model_Store::CACHE_TAG, Mage_Catalog_Model_Category::CACHE_TAG . '_' . $currentCategory->getId());

            $this->addCacheInfo($block, $lifetime, $key, $tags);
        }

        return $this;
    }

    /**
     * Create cache for product view with all cacheable children blocks
     * 
     * @param object $block
     * @param int $storeId
     * @return Soon_AdvancedCache_Model_Add 
     */
    public function addProductView($block, $storeId) {
        if (Mage::registry('current_product') != '') {
            $product = Mage::registry('current_product');

            $lifetime = $this->getCacheConfig()->getExpire('catalog');
            $tags = array(Mage_Core_Model_Store::CACHE_TAG, Mage_Catalog_Model_Product::CACHE_TAG . '_' . $product->getId());

            // Retrieve children blocks names
            $childrenBlocksNames = $block->getSortedChildren();

            // Define restricted blocks not to be cached.
            // Restricted blocks are those which must be updated in real time, ie: product options.
            // @var array $restrictedBlocksNames
            // @param string blocks names in layout
            $restrictedBlocksNames = array('product.info.options.wrapper', 'product.info.options', 'product.info.options.configurable');

            // Add children blocks to cache except those which are restricted
            foreach ($childrenBlocksNames as $blockName) {
                $key = Mage_Catalog_Model_Product::CACHE_TAG . '_' . $product->getId();
                if (!in_array($blockName, $restrictedBlocksNames)) {
                    $key .= '_' . $blockName . '_' . $storeId;
                    $blockToCache = Mage::app()->getLayout()->getBlock($blockName);
                    $this->addCacheInfo($blockToCache, $lifetime, $key, $tags);
                }
            }

            return $this;
        }
    }

    /**
     * Add block to cache
     * 
     * @param object $block
     * @param int $lifetime
     * @param string $key
     * @param array $tags
     * @return Soon_AdvancedCache_Model_Add 
     */
    public function addCacheInfo($block, $lifetime, $key, $tags) {
        if (is_object($block) && $this->isBlockCachable($block)) {
            $block->setData('cache_lifetime', $lifetime);
            $block->setData('cache_key', $key);
            $block->setData('cache_tags', $tags);

            Mage::dispatchEvent('advancedcache_add', array('block' => $block, 'lifetime' => $lifetime, 'key' => $key, 'tags' => $tags));
        }

        return $this;
    }

    /**
     * Check if block can be added to cache.
     * If system messages are stored to session, the block is not cachable.
     * 
     * @param object $block
     * @return bool
     */
    public function isBlockCachable($block) {
        $messageHtml = $block->getMessagesBlock()->getGroupedHtml();
        return empty($messageHtml);
    }

    /**
     * Retrieve post request
     * 
     * @return Mage_Core_Controller_Request_Http
     */
    protected function _getRequest() {
        if (!isset($this->_request)) {
            $this->_request = Mage::app()->getRequest();
        }

        return $this->_request;
    }

}
