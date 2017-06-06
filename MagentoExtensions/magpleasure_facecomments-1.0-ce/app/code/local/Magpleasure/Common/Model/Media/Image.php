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

/**
 * Media Image Model
 */
class Magpleasure_Common_Model_Media_Image extends Mage_Catalog_Model_Product_Image
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

    /**
     * Set filenames for base file and new file
     *
     * @param string $file
     * @return $this|Mage_Catalog_Model_Product_Image
     * @throws Exception
     */
    public function setBaseFile($file)
    {
        $file = trim($file, "/");
        $file = "/".$file;
        $baseFile = Mage::getBaseDir('media').str_replace("/", DS, $file);

        if ((!$file) || (!file_exists($baseFile))) {
            throw new Exception(Mage::helper('catalog')->__('Image file was not found.'));
        }

        $this->_baseFile = $baseFile;

        # build new filename (most important params)
        $path = array(
            $this->_commonHelper()->getFiles()->getDirName($baseFile),
            'cache',
            Mage::app()->getStore()->getId()
        );

        if (empty($this->_height)){
            $this->_height = $this->_width;
        }

        if((!empty($this->_width)) || (!empty($this->_height)))
            $path[] = "{$this->_width}x{$this->_height}";

        // add misk params as a hash
        $miscParams = array(
            ($this->_keepAspectRatio  ? '' : 'non') . 'proportional',
            ($this->_keepFrame        ? '' : 'no')  . 'frame',
            'angle' . $this->_angle,
            'quality' . $this->_quality
        );

        $path[] = md5(implode('_', $miscParams));
        $baseFileName = $this->_commonHelper()->getFiles()->getBaseName($baseFile);

        # append prepared filename
        $this->_newFile = implode(DS, $path). DS . $baseFileName;

        return $this;
    }

}