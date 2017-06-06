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

class Magpleasure_Common_Block_Adminhtml_Widget_Treeview_Tree extends Magpleasure_Common_Block_Adminhtml_Template
{
    protected $_model;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("magpleasure/treeview/tree.phtml");
    }

    /**
     * @param $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->_model = $model;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->_model;
    }

    public function getJsObjectName()
    {
        return $this->getId()."TreeviewTree";
    }

    public function getHtmlId()
    {
        return strtolower($this->getId())."_tree";
    }

    public function getDataUrl()
    {
        $params = array();
        return $this->getUrl('magpleasure_admin/adminhtml_treeview/list', $params);
    }
}

