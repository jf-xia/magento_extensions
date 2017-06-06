<?php 
class Wee_Fpc_Model_Processor
{
    public function extractContent($content)
    {
        $fullPageCache = new Wee_Fpc_Model_FullPageCache();
        $cacheKey = $fullPageCache->getCacheKey();
        $cache = $fullPageCache->load($cacheKey);
        if ($cache) {
            $cacheMetaDatas = $fullPageCache->getMetadatas($cacheKey);
            $date = new Zend_Date();
            $date->setTimestamp($cacheMetaDatas['expire']);
            Mage::app()->getResponse()->setHeader('Fpc-Expire', $date->toString(Zend_Date::DATETIME_MEDIUM));
            $content = $cache['output'];
            $requestParameter = $cache['requestParameter'];
            return self::_prepareContent($content, $requestParameter);
        }
        return;
    }

    static protected function _prepareContent($content, array $requestParameter)
    {
        $processors = Mage::app()->getConfig()->getNode('frontend/wee_fpc/content_processors')->asCanonicalArray();
        if ($processors) {
            Mage::app()->getArea('frontend')->load('translate');
            foreach ($processors as $processorClass) {
                $processor = self::_getProcessor($processorClass);
                $content = $processor->prepareContent($content, $requestParameter);
            }
        }
        return $content;
    }
    
    static function _getProcessor($class)
    {
        return new $class();
    }
}
?>