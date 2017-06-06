<?php

class Wee_Fpc_Helper_Data extends Mage_Core_Helper_Abstract
{
    static public function isEnabled()
    {
        return Wee_Fpc_Model_FullPageCache::isCacheEnabled();
    }
    
    static function getPattern($key)
    {
        return sprintf('/%s(.*?)%s/ims', self::getStartPattern($key), self::getEndPattern($key));
    }
    
    static public function getStartPattern($key)
    {
        return sprintf('<!--%s_start-->', $key);
    }
    
    static public function getEndPattern($key)
    {
        return sprintf('<!--%s_end-->', $key);
    }
}
