<?php
class Activo_Categoryurlseo_Model_Url extends Mage_Catalog_Model_Url
{
    /**
     * Get unique product request path
     *
     * @param   Varien_Object $product
     * @param   Varien_Object $category
     * @return  string
     */
    public function getProductRequestPath($product, $category)
    {
        if (Mage::getStoreConfig('activo_categoryurlseo/global/enabled')==0)
        {
            return parent::getProductRequestPath($product, $category);
        }
        else
        {
            if ($product->getUrlKey() == '') {
                $urlKey = $this->getProductModel()->formatUrlKey($product->getName());
            } else {
                $urlKey = $this->getProductModel()->formatUrlKey($product->getUrlKey());
            }
            $storeId = $category->getStoreId();
            $suffix  = $this->getProductUrlSuffix($storeId);
            $idPath  = $this->generatePath('id', $product, $category);
            /**
             * Prepare product base request path
             */
            if ($category->getLevel() > 1) {
                // To ensure, that category has path either from attribute or generated now
                $this->_addCategoryUrlPath($category);
                $categoryUrl = Mage::helper('catalog/category')->getCategoryUrlPath($category->getUrlPath(),
                    false, $storeId);
                if ($categoryUrl == "")
                {
                    $requestPath = $urlKey;
                }
                else
                {
                    $requestPath = $categoryUrl . '/' . $urlKey;
                }
                $requestPath = str_ireplace('//', '/', $requestPath);
            } else {
                $requestPath = $urlKey;
            }

            if (strlen($requestPath) > self::MAX_REQUEST_PATH_LENGTH + self::ALLOWED_REQUEST_PATH_OVERFLOW) {
                $requestPath = substr($requestPath, 0, self::MAX_REQUEST_PATH_LENGTH);
            }

            $this->_rewrite = null;
            /**
             * Check $requestPath should be unique
             */
            if (isset($this->_rewrites[$idPath])) {
                $this->_rewrite = $this->_rewrites[$idPath];
                $existingRequestPath = $this->_rewrites[$idPath]->getRequestPath();
                $existingRequestPath = str_replace($suffix, '', $existingRequestPath);

                if ($existingRequestPath == $requestPath) {
                    return $requestPath.$suffix;
                }
                /**
                 * Check if existing request past can be used
                 */
                if ($product->getUrlKey() == '' && !empty($requestPath)
                    && strpos($existingRequestPath, $requestPath) !== false
                ) {
                    $existingRequestPath = str_replace($requestPath, '', $existingRequestPath);
                    if (preg_match('#^-([0-9]+)$#i', $existingRequestPath)) {
                        return $this->_rewrites[$idPath]->getRequestPath();
                    }
                }
                /**
                 * check if current generated request path is one of the old paths
                 */
                $fullPath = $requestPath.$suffix;
                $finalOldTargetPath = $this->getResource()->findFinalTargetPath($fullPath, $storeId);
                if ($finalOldTargetPath && $finalOldTargetPath == $idPath) {
                    $this->getResource()->deleteRewrite($fullPath, $storeId);
                    return $fullPath;
                }
            }
            /**
             * Check 2 variants: $requestPath and $requestPath . '-' . $productId
             */
            $validatedPath = $this->getResource()->checkRequestPaths(
                array($requestPath.$suffix, $requestPath.'-'.$product->getId().$suffix),
                $storeId
            );

            if ($validatedPath) {
                return $validatedPath;
            }
            /**
             * Use unique path generator
             */
            return $this->getUnusedPath($storeId, $requestPath.$suffix, $idPath);
        }
    }
}
