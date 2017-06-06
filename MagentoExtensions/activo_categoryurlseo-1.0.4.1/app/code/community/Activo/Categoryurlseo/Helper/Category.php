<?php

class Activo_Categoryurlseo_Helper_Category extends Mage_Catalog_Helper_Category
{
    /**
     * Retrieve clear url for category as parrent
     *
     * @param string $url
     * @param bool $slash
     * @param int $storeId
     *
     * @return string
     */
    public function getCategoryUrlPath($urlPath, $slash = false, $storeId = null)
    {
        if (Mage::getStoreConfig('activo_categoryurlseo/global/enabled')==0)
        {
            return parent::getCategoryUrlPath($urlPath, $slash, $storeId);
        }
        else
        {
            return Mage::getStoreConfig('activo_categoryurlseo/global/toplevel');
        }
    }
}
