<?php
 /*
 * Arcanum Dev AwardPoints
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to arcanumdev@wafunotamago.com so we can send you a copy immediately.
 *
 * @category   Magento Sale Extension
 * @package    AwardPoints
 * @copyright  Copyright (c) 2012 Arcanum Dev. Y.K. (http://arcanumdev.wafunotamago.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Arcanumdev_Awardpoints_Block_Adminhtml_Stats_Checkpoints extends Mage_Adminhtml_Block_Widget_Form_Container{public function __construct(){parent::__construct();$this->_objectId='id';$this->_blockGroup='awardpoints';$this->_controller='adminhtml_stats';$this->_updateButton('save', 'label', Mage::helper('awardpoints')->__('Submit checking'));$this->_formScripts[]="";}public function getHeaderText(){return Mage::helper('awardpoints')->__('Refresh customer points');}public function getFormHtml(){return $this->getLayout()->createBlock('awardpoints/adminhtml_stats_edit_checkform')->setAction($this->getSaveUrl())->toHtml();}public function getSaveUrl(){return $this->getUrl('*/*/savecheck', array('_current'=>true));}}