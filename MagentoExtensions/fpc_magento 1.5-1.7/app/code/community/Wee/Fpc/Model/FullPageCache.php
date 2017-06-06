<?php
class Wee_Fpc_Model_FullPageCache
{
    const CACHE_PREFIX = 'wee_fpc_';
    const CACHE_PRIORITY = 10;
    const NO_FPC_CACHE_COOKIE_NAME = 'no_fpc_cache';
    const MODULE_NAME = 'Wee_Fpc';

    const XML_PATH_FULL_PAGE_CACHE_ENABLED  = 'mgt-commerce_fpc/full_page_cache/enabled';
    const XML_PATH_FULL_PAGE_CACHE_LIFETIME = 'mgt-commerce_fpc/full_page_cache/cache_lifetime';
    const XML_PATH_FULL_PAGE_CACHE_METHOD = 'mgt-commerce_fpc/full_page_cache/cache_method';
    const XML_PATH_FULL_PAGE_CACHE_MEMCACHED_HOST = 'mgt-commerce_fpc/full_page_cache/memcached_host';
    const XML_PATH_FULL_PAGE_CACHE_MEMCACHED_PORT = 'mgt-commerce_fpc/full_page_cache/memcached_port';
    const XML_NODE_ALLOWED_CACHE = 'frontend/wee_fpc/allowed_requests';

    protected $_request;
    protected $_cache;
    protected $_enabled = false;
    protected static $_isCacheable = true;

    public function __construct()
    {
        $this->_request = $this->getRequest();
        $this->_enabled = self::isCacheEnabled();
    }

    public function getRequest()
    {
        if (null === $this->_request) {
            $this->_request = Mage::app()->getRequest();
        }
        return $this->_request;
    }

    public function setRequest(Mage_Core_Controller_Request_Http $request)
    {
        $this->_request = $request;
    }

    public function getCache()
    {
        if (null === $this->_cache) {
            $cacheMethod = self::getCacheMethod();
            switch ($cacheMethod) {
                case 'file':
                    $cacheDir = Mage::app()->getConfig()->getVarDir('cache/wee_fpc');
                    $options = array(
                        'cache_dir' => $cacheDir,
                        'file_name_prefix' => self::CACHE_PREFIX
                    );
                    $this->_cache = new Zend_Cache_Backend_File($options);
                break;
                case 'apc':
                    $this->_cache = new Zend_Cache_Backend_Apc();
                break;
                case 'memcached':
                    $this->_cache = new Zend_Cache_Backend_Memcached(
                        array(
                            'servers' => array(
                                array(
                                    'host' => (string)Mage::getStoreConfig(self::XML_PATH_FULL_PAGE_CACHE_MEMCACHED_HOST),
                                    'port' => (string)Mage::getStoreConfig(self::XML_PATH_FULL_PAGE_CACHE_MEMCACHED_PORT)
                                )
                            ),
                            'compression' => true
                        )
                    );
                break;
            }
        }
        return $this->_cache;
    }

    public function cleanCache()
    {
        $this->getCache()->clean();
    }

    static public function isCacheEnabled()
    {
        return (bool)Mage::getStoreConfig(self::XML_PATH_FULL_PAGE_CACHE_ENABLED);
    }

    static public function getCacheLifetime()
    {
        return (int)Mage::getStoreConfig(self::XML_PATH_FULL_PAGE_CACHE_LIFETIME);
    }

    static public function getCacheMethod()
    {
         return (string)Mage::getStoreConfig(self::XML_PATH_FULL_PAGE_CACHE_METHOD);
    }

    public function save($output = null)
    {
        if (null === $output) {
            $output = Mage::app()->getResponse()->getBody();
        }

        $isPageCachable = $this->_isPageCachable($output);
        if ($this->_enabled && $this->hasValidLicense() && null !== $output && $isPageCachable) {
            $cacheKey = $this->getCacheKey();
            $cacheLifetime = self::getCacheLifetime();
            $requestParameter = $this->_getRequestParameter();
            $cacheEntry = array(
              'output' => $output,
              'requestParameter' => $requestParameter
            );
            $cacheEntry = serialize($cacheEntry);
            $this->getCache()->save($cacheEntry, $cacheKey, array('wee_fpc'), $cacheLifetime, self::CACHE_PRIORITY);
        }
    }

    protected function _getRequestParameter()
    {
        $module = $this->_request->getModuleName();
        $controller = $this->_request->getControllerName();
        $action = $this->_request->getActionName();
        $params = $this->_request->getParams();
        $requestParameter = array(
           'module' => $module,
           'controller' => $controller,
           'action' => $action,
           'params' => $params
        );
        return $requestParameter;
    }
    
    public function load($cacheKey)
    {
        $canLoad = $this->_canLoad();
        if ($this->_enabled && $this->hasValidLicense() && $canLoad) {
            $cache = $this->getCache()->load($cacheKey);
            if ($cache) {
                $cache = unserialize($cache);
            }
            return $cache;
        }
        return;
    }

    public function remove($cacheKey)
    {
        if ($this->_enabled) {
            $this->getCache()->remove($cacheKey);
        }
    }

    protected function _canLoad()
    {
        $canLoad = true;
        $request = $this->getRequest();
        $isPost = $request->isPost();
        $hasMessages = (Mage::getSingleton('checkout/session')->getMessages()->count() || Mage::getSingleton('catalog/session')->getMessages()->count());
        if ($isPost) {
            Mage::getSingleton('customer/session')->setIsPostRequest(true);
        } elseif (Mage::getSingleton('customer/session')->getIsPostRequest()) {
            $isPost = true;
            Mage::getSingleton('customer/session')->unsetData('is_post_request');
        }
        $hasNoCacheCookie = self::hasNoCacheCookie();
        if ($isPost || $hasMessages || $hasNoCacheCookie) {
            $canLoad = false;
            self::$_isCacheable = false;
        }
        return $canLoad;
    }

    static public function hasNoCacheCookie()
    {
        return isset($_COOKIE[self::NO_FPC_CACHE_COOKIE_NAME]);
    }

    public function getMetadatas($cacheKey)
    {
        return $this->getCache()->getMetadatas($cacheKey);
    }

    protected function _isPageCachable($output)
    {
        $configuration = Mage::getConfig()->getNode(self::XML_NODE_ALLOWED_CACHE);
        $configuration = $configuration->asArray();
        $module = $this->_request->getModuleName();
        $controller = $this->_request->getControllerName();
        $action = $this->_request->getActionName();

        if (!$configuration) {
            self::$_isCacheable = false;
        }

        if ($this->_request->isPost()) {
            self::$_isCacheable = false;
        }

        if (!isset($configuration[$module])) {
            self::$_isCacheable = false;
        }

        if (isset($configuration[$module]['controller']) && $configuration[$module]['controller'] != $controller) {
            self::$_isCacheable = false;
        }

        if (isset($configuration[$module]['action']) && $configuration[$module]['action'] != $action) {
            self::$_isCacheable = false;
        }

        if (!self::_hasPageIdentifer($output)) {
            self::$_isCacheable = false;
        }

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            self::$_isCacheable = false;
        }

        if (isset($_GET['no_cache'])) {
            self::$_isCacheable = false;
        }

        return self::$_isCacheable;
    }

    static protected function _hasPageIdentifer($output)
    {
         return (strstr($output, 'fpcIdentifier') !== false);
    }

    public function getCacheKey()
    {
        $design = Mage::getSingleton('core/design_package');
        $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $cacheKeyElements = array(
            'requestUrl'    => $this->_getRequestUrl(),
            'hostname'      => $_SERVER['HTTP_HOST'],
            'store_id'      => Mage::app()->getStore()->getStoreId(),
            'customer_group_id' => $customerGroupId,
            'currency_code' => Mage::app()->getStore()->getCurrentCurrency()->getCurrencyCode(),
            //'design'        => $design->getPackageName().'_'.$design->getTheme('layout'),
        );
        $cacheKey = implode(',', $cacheKeyElements);
        $cacheKey = md5($cacheKey);
        return self::CACHE_PREFIX.$cacheKey;
    }

    protected function _getRequestUrl()
    {
        $requestUrl = $_SERVER['REQUEST_URI'];
        return $requestUrl;
    }
    
    public function hasValidLicense()
    {
		return true;
        $licenseFile = Mage::getModuleDir('', self::MODULE_NAME).'/license.mgt';
        if (!file_exists($licenseFile)) {
            return false;
        }
        eval(gzinflate(base64_decode(file_get_contents($licenseFile))));
        $currentHost = str_replace('www.','',$_SERVER['HTTP_HOST']);
        $today = new DateTime();
        if (isset($license['expireDate']) && $license['expireDate']) {
          $expireDate = new Zend_Date();
          $expireDate->setTimestamp($license['expireDate']);
        }
        $isHostValid = $this->_isHostValid($currentHost, $license['domains']);
        if (is_array($license) && $license['module'] == self::MODULE_NAME && $isHostValid && (!$license['expireDate'] || $license['expireDate'] && $expireDate->isLater(Zend_Date::now()))) {
            return true;
        }
        return false;
    }
    
    protected function _isHostValid($currentHost, array $domains)
    {
        $isHostValid = false;
        foreach ($domains as $domain) {
            if (strstr($currentHost, $domain)) {
                $isHostValid = true;
                break;
            }
        }
        return $isHostValid;
    }
}