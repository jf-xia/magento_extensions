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

class Magpleasure_Common_Block_Adminhtml_Widget_Treeview_Container
    extends Magpleasure_Common_Block_Adminhtml_Widget_Ajax_Form_Container
{
    protected $_model;

    protected function _construct()
    {
        parent::_construct();
        $this->_initTreeModel();
    }

    /**
     * @param $model
     * @return $this
     */
    public function setModel($model)
    {
        if (is_object($model)){
            $this->_model = $model;
        } else {
            try {
                $this->_model = Mage::getModel($model);
            } catch (Exception $e){
                $this->_commonHelper()->getException()->logException($e);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->_model;
    }

    protected function _initTreeModel()
    {
        # Set Model here...
        return $this;
    }

    protected function _toHtml()
    {
        return "8p";
    }
}

