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
 * @package    Magpleasure_Filesystem
 * @version    1.0
 * @copyright  Copyright (c) 2011 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */


class Magpleasure_Filesystem_Block_Adminhtml_Ide_Tree extends Mage_Adminhtml_Block_Abstract
{
    /**
     * Template path
     */
    const TEMPLATE_PATH = 'filesystem/ide/tree.phtml';
    
    protected function _construct() 
    {
        parent::_construct();
        $this->setTemplate(self::TEMPLATE_PATH);
    }
    
    public function getTreeHtml()
    {
        $url = $this->getUrl("filesystem/adminhtml_filesystem/load", array('fn'=>"{{filename}}"));
        return Mage::getModel('filesystem/tree')->php_file_tree(Mage::getBaseDir(), "javascript:openFile('{$url}','[link]');");
    }
    
    public function getFormKey()
    {
        return Mage::getSingleton('core/session')->getFormKey();
    }
    
}