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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Stats_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{public function __construct(){parent::__construct();$this->_objectId='id';$this->_blockGroup='awardpoints';$this->_controller='adminhtml_stats';$this->_updateButton('save','label',Mage::helper('awardpoints')->__('Save Point'));$this->_updateButton('delete', 'label',Mage::helper('awardpoints')->__('Delete Point'));$this->_formScripts[]=" checkTarget = function(){ if ($('target_type').getValue() == ".Arcanumdev_Awardpoints_Model_Stats::TARGET_FREE."){ $('order_id').value = '".Arcanumdev_Awardpoints_Model_Stats::APPLY_ALL_ORDERS."'; $('order_id').up(1).hide(); } else { $('order_id').value = ''; $('order_id').up(1).show(); } };    ";}public function getHeaderText(){if(Mage::registry('stat_data')&&Mage::registry('stat_data')->getId()){return Mage::helper('awardpoints')->__('Edit Point');}else{return Mage::helper('awardpoints')->__('Add Point'); } } public function getFormHtml(){return $this->getLayout() ->createBlock('awardpoints/adminhtml_stats_edit_form') ->setAction($this->getSaveUrl()) ->toHtml();}public function getSaveUrl(){return $this->getUrl('*/*/save',array('_current'=>true));}}