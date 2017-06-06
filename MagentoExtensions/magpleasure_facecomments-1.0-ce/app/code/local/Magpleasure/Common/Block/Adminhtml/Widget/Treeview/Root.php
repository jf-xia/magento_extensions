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

class Magpleasure_Common_Block_Adminhtml_Widget_Treeview_Root extends Magpleasure_Common_Block_Adminhtml_Template
{
    const TREE_BLOCK = 'magpleasure/adminhtml_widget_treeview_tree';

    protected $_model;
    protected $_container;
    protected $_defaultContainer;
    protected $_id = 'default';

    protected $_paramsToTransfer = array();

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("magpleasure/treeview/root.phtml");
    }

    /**
     * @param $defaultContainer
     * @return $this
     */
    public function setDefaultContainer($defaultContainer)
    {
        $this->_defaultContainer = $defaultContainer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultContainer()
    {
        return $this->_defaultContainer;
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

    /**
     * @param $container
     * @return $this
     */
    public function setContainer($container)
    {
        $this->_container = $container;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->_container;
    }

    /**
     * @param mixed $id
     * @return $this|Varien_Object
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    public function getJsObjectName()
    {
        return $this->getId()."TreeviewJsObject";
    }

    public function getTreeHtml()
    {
        /** @var Magpleasure_Common_Block_Adminhtml_Widget_Treeview_Tree $tree */
        $tree = $this->getLayout()->createBlock(self::TREE_BLOCK);
        if ($tree){
            $tree
                ->setModel($this->getModel())
                ->setId($this->getId())
            ;

            return $tree->toHtml();
        }

        return false;
    }

    public function getContainerHtml()
    {
        if ($id = $this->getRequest()->getParam('id')){

        } elseif ($defaultContainer = $this->getDefaultContainer()) {

            $defaultContainer = $this->getLayout()->createBlock($defaultContainer);
            if ($defaultContainer){
                return $defaultContainer->toHtml();
            }
        }

        return false;
    }

    public function addParamToTransfer($key)
    {
        $this->_paramsToTransfer[] = $key;
        return $this;
    }
}

