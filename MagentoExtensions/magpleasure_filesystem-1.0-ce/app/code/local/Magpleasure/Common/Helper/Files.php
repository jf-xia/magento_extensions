<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Common
 * @version    0.6.11
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */
class Magpleasure_Common_Helper_Files extends Mage_Core_Helper_Abstract
{
    /**
     * Helper
     *
     * @return Magpleasure_Common_Helper_Data
     */
    protected function _commonHelper()
    {
        return Mage::helper('magpleasure');
    }

    protected function _getPathInfo($fileName, $key)
    {
        $pathParts = pathinfo($fileName);
        return isset($pathParts[$key]) ? $pathParts[$key] : false;
    }

    public function getBaseName($fileName)
    {
        return $this->_getBaseName($fileName);
    }

    public function getFileName($fileName)
    {
        return $this->_getFileName($fileName);
    }

    public function getDirName($fileName)
    {
        return $this->_getDirName($fileName);
    }

    protected function getExtension($fileName)
    {
        return $this->_getExtension($fileName);
    }

    protected function _getDirName($fileName)
    {
        return $this->_getPathInfo($fileName, 'dirname');
    }

    protected function _getFileName($fileName)
    {
        return $this->_getPathInfo($fileName, 'filename');
    }

    protected function _getBaseName($fileName)
    {
        return $this->_getPathInfo($fileName, 'basename');
    }

    protected function _getExtension($fileName)
    {
        return $this->_getPathInfo($fileName, 'extension');
    }

    public function saveContentToFile($fileName, $data, $overwrite = true, &$fileObject = null)
    {
        if ($fileName){

            if (!file_exists($fileName)){

                $dirName = $this->_getDirName($fileName);
                if (!file_exists($dirName)){
                    mkdir($dirName, 0755, true);
                }

                file_put_contents($fileName, $data);
            } else {
                ///TODO
                file_put_contents($fileName, $data);
            }
        }

        return $this;
    }

    public function getContentFromFile($fileName)
    {
        return file_get_contents($fileName);
    }

}