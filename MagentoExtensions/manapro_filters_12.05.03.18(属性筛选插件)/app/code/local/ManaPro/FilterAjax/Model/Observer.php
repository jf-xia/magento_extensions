<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

/* BASED ON SNIPPET: Models/Observer */
/**
 * This class observes certain (defined in etc/config.xml) events in the whole system and provides public methods - handlers for
 * these events.
 * @author Mana Team
 *
 */
class ManaPro_FilterAjax_Model_Observer {
	protected $_registered = false;
	/* BASED ON SNIPPET: Models/Event handler */
	/**
	 * Register URL for AJAXifying it when page is loaded (handles event "controller_action_layout_load_before")
	 * @param Varien_Event_Observer $observer
	 */
	public function registerUrl($observer) {
		if (!$this->_registered) {
			/* @var $route string */ $route = $observer->getEvent()->getRoute();
			/* @var $params array */ $params = $observer->getEvent()->getParams();
			/* @var $url string */ $url = $observer->getEvent()->getUrl();
			/* @var $request Mage_Core_Controller_Request_Http */ $request = Mage::app()->getRequest();
			if ($request->getModuleName() == 'catalog' && $request->getControllerName() == 'category' && $request->getActionName() == 'view') 
			{
				/* @var $category Mage_Catalog_Model_Category */ $category = Mage::registry('current_category');
				/* @var $core Mana_Core_Helper_Data */ $core = Mage::helper(strtolower('Mana_Core'));
				/* @var $helper ManaPro_FilterAjax_Helper_Data */ $helper = Mage::helper(strtolower('ManaPro_FilterAjax'));
				
				Mana_Core_Profiler::start('mln', __CLASS__, __METHOD__, '$category->getUrl()');
				$url = Mage::helper('mana_filters')->getClearUrl(false, true);//$category->getUrl();
                if (($pos = mb_strrpos($url, '?')) !== false) {
                    $url = mb_substr($url, 0, $pos);
                }
                Mana_Core_Profiler::stop('mln', __CLASS__, __METHOD__, '$category->getUrl()');
				//if ($core->endsWith($url, '/')) {
				//	$url = substr($url, 0, strlen($url) - 1);
				//}
				$helper->registerExactUrl($url);
				$helper->registerPartialUrl($url.'?');
				if ($categorySuffix = Mage::helper('catalog/category')->getCategoryUrlSuffix()) {
					if (($pos = mb_strrpos($url, $categorySuffix)) !== false) {
						if ($pos + mb_strlen($categorySuffix) < mb_strlen($url)) {
							$url = mb_substr($url, 0, $pos).mb_substr($url, $pos + mb_strlen($categorySuffix));
						}
						else {
							$url = mb_substr($url, 0, $pos);
						}
					}
					if ($conditionalWord = $core->getStoreConfig('mana_filters/seo/conditional_word')) {
						$helper->registerPartialUrl($url.'/'.$conditionalWord);
					}
				}
				else {
					if ($conditionalWord = $core->getStoreConfig('mana_filters/seo/conditional_word')) {
						$helper->registerPartialUrl($url.'/'.$conditionalWord);
					}
				}
				
				Mana_Core_Profiler::start('mln', __CLASS__, __METHOD__, '$category->getChildrenCategories()');
				$childCategories = $category->getChildrenCategories();
				Mana_Core_Profiler::stop('mln', __CLASS__, __METHOD__, '$category->getChildrenCategories()');
				foreach ($childCategories as $childCategory) {
					$url = $childCategory->getUrl();
                    if (Mage::app()->getFrontController()->getRequest()->isSecure()) {
                        $url = str_replace('http://', 'https://', $url);
                    }
					if ($core->endsWith($url, '/')) {
						$url = substr($url, 0, strlen($url) - 1);
					}
					if ($categorySuffix = Mage::helper('catalog/category')->getCategoryUrlSuffix()) {
						$url = str_replace($categorySuffix, '', $url);
					}
					$helper->registerUrlException($url);
				}
			}
			elseif ($request->getModuleName() == 'catalogsearch' && $request->getControllerName() == 'result' && $request->getActionName() == 'index' ) 
			{
				/* @var $category Mage_Catalog_Model_Category */ $category = Mage::registry('current_category');
				/* @var $core Mana_Core_Helper_Data */ $core = Mage::helper(strtolower('Mana_Core'));
				/* @var $helper ManaPro_FilterAjax_Helper_Data */ $helper = Mage::helper(strtolower('ManaPro_FilterAjax'));
				
				$url = Mage::getSingleton('core/url')->sessionUrlVar(Mage::helper('core')->escapeUrl(Mage::getUrl()));
				if ($core->endsWith($url, '/')) {
					$url = substr($url, 0, strlen($url) - 1);
				}
				$url .= $request->getOriginalPathInfo();
				$helper->registerPartialUrl($url);
			}
            elseif ($request->getModuleName() == 'cms' && $request->getControllerName() == 'page' && $request->getActionName() == 'view')
            {
                /* @var $core Mana_Core_Helper_Data */ $core = Mage::helper(strtolower('Mana_Core'));
                /* @var $helper ManaPro_FilterAjax_Helper_Data */ $helper = Mage::helper(strtolower('ManaPro_FilterAjax'));

                $url = Mage::helper('cms/page')->getPageUrl($request->getParam('page_id'));
                $helper->registerExactUrl($url);
                $helper->registerPartialUrl($url.'?');
                if ($conditionalWord = $core->getStoreConfig('mana_filters/seo/conditional_word')) {
                    $helper->registerPartialUrl($url.'/'.$conditionalWord);
                }
            }
            elseif ($request->getModuleName() == 'cms' && $request->getControllerName() == 'index' && $request->getActionName() == 'index')
            {
                /* @var $core Mana_Core_Helper_Data */ $core = Mage::helper(strtolower('Mana_Core'));
                /* @var $helper ManaPro_FilterAjax_Helper_Data */ $helper = Mage::helper(strtolower('ManaPro_FilterAjax'));

                $url = Mage::getUrl();
                $helper->registerExactUrl($url);
                $helper->registerPartialUrl($url.'?');
                if ($conditionalWord = $core->getStoreConfig('mana_filters/seo/conditional_word')) {
                    $helper->registerPartialUrl($url.$conditionalWord);
                }
            }
			$this->_registered = true;
		}
	}
	/* BASED ON SNIPPET: Models/Event handler */
	/**
	 * Calls minimized version of category action (handles event "controller_action_predispatch_catalog_category_view")
	 * @param Varien_Event_Observer $observer
	 */
	public function ajaxCategoryView($observer) {
		/* @var $action Mage_Catalog_CategoryController */ $action = $observer->getEvent()->getControllerAction();
		if ($action->getRequest()->getParam('m-ajax') == 1) {
			if (isset($_GET['m-ajax'])) unset($_GET['m-ajax']);
			if (isset($_POST['m-ajax'])) unset($_POST['m-ajax']);
			$_SERVER['REQUEST_URI'] = str_replace('?m-ajax=1', '', $_SERVER['REQUEST_URI']);
			$_SERVER['REQUEST_URI'] = str_replace('&m-ajax=1', '', $_SERVER['REQUEST_URI']);
			$this->_forward($action->getRequest(), 'view', 'category', 'manapro_filterajax');
		}
	}
	/* BASED ON SNIPPET: Models/Event handler */
	/**
	 * Calls minimized version of search action (handles event "controller_action_predispatch_catalogsearch_result_index")
	 * @param Varien_Event_Observer $observer
	 */
	public function ajaxSearchResult($observer) {
		/* @var $action Mage_CatalogSearch_ResultController */ $action = $observer->getEvent()->getControllerAction();
		if ($action->getRequest()->getParam('m-ajax') == 1) {
			if (isset($_GET['m-ajax'])) unset($_GET['m-ajax']);
			if (isset($_POST['m-ajax'])) unset($_POST['m-ajax']);
			$_SERVER['REQUEST_URI'] = str_replace('?m-ajax=1', '', $_SERVER['REQUEST_URI']);
			$_SERVER['REQUEST_URI'] = str_replace('&m-ajax=1', '', $_SERVER['REQUEST_URI']);
			$this->_forward($action->getRequest(), 'index', 'search', 'manapro_filterajax');
		}
	}
	
	/* BASED ON SNIPPET: Models/Event handler */
	/**
	 * Marks block output to be replaceable (handles event "core_block_abstract_to_html_after")
	 * @param Varien_Event_Observer $observer
	 */
	public function markUpdatableHtml($observer) {
		/* @var $block Mage_Core_Block_Abstract */ $block = $observer->getEvent()->getBlock();
		/* @var $transport Varien_Object */ $transport = $observer->getEvent()->getTransport();
		
		if ($block->getLayout() && ($updateBlock = $block->getLayout()->getBlock('m_ajax_update'))) {
			$transport->setHtml($updateBlock->markUpdatable($block->getNameInLayout(), $transport->getHtml()));
		}
	}
	
	protected function _forward($request, $action, $controller = null, $module = null, array $params = null)
    {
        $request->initForward();

        if (!is_null($params)) {
            $request->setParams($params);
        }

        if (!is_null($controller)) {
            $request->setControllerName($controller);

            // Module should only be reset if controller has been specified
            if (!is_null($module)) {
                $request->setModuleName($module);
            }
        }

        $request->setActionName($action)
            ->setDispatched(false);
    }
    public function ajaxCmsIndex($observer) {
        /* @var $action Mage_Catalog_CategoryController */ $action = $observer->getEvent()->getControllerAction();
        if ($action->getRequest()->getParam('m-ajax') == 1) {
            if (isset($_GET['m-ajax'])) unset($_GET['m-ajax']);
            if (isset($_POST['m-ajax'])) unset($_POST['m-ajax']);
            $_SERVER['REQUEST_URI'] = str_replace('?m-ajax=1', '', $_SERVER['REQUEST_URI']);
            $_SERVER['REQUEST_URI'] = str_replace('&m-ajax=1', '', $_SERVER['REQUEST_URI']);
            $this->_forward($action->getRequest(), 'index', 'index', 'manapro_filterajax');
        }
    }
    public function ajaxCmsPage($observer) {
        /* @var $action Mage_Catalog_CategoryController */ $action = $observer->getEvent()->getControllerAction();
        if ($action->getRequest()->getParam('m-ajax') == 1) {
            if (isset($_GET['m-ajax'])) unset($_GET['m-ajax']);
            if (isset($_POST['m-ajax'])) unset($_POST['m-ajax']);
            $_SERVER['REQUEST_URI'] = str_replace('?m-ajax=1', '', $_SERVER['REQUEST_URI']);
            $_SERVER['REQUEST_URI'] = str_replace('&m-ajax=1', '', $_SERVER['REQUEST_URI']);
            $this->_forward($action->getRequest(), 'view', 'page', 'manapro_filterajax');
        }
    }
}