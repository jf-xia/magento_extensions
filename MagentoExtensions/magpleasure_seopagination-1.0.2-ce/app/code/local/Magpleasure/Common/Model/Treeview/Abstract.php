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
 * Abstract TreeView Model
 */
class Magpleasure_Common_Model_Treeview_Abstract extends Magpleasure_Common_Model_Abstract
{
    protected $_positonFieldName = 'position';
    protected $_parentFieldName = 'parent_id';
    protected $_pathFieldName = 'path';

    /**
     * @param $pathFieldName
     * @return $this
     */
    public function setPathFieldName($pathFieldName)
    {
        $this->_pathFieldName = $pathFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathFieldName()
    {
        return $this->_pathFieldName;
    }

    /**
     * @param $parentFieldName
     * @return $this
     */
    public function setParentFieldName($parentFieldName)
    {
        $this->_parentFieldName = $parentFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getParentFieldName()
    {
        return $this->_parentFieldName;
    }

    /**
     * @param $positonFieldName
     * @return $this
     */
    public function setPositonFieldName($positonFieldName)
    {
        $this->_positonFieldName = $positonFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPositonFieldName()
    {
        return $this->_positonFieldName;
    }

    public function canBeParent()
    {
        return true;
    }
}