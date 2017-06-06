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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Pointrules_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{public function __construct(){parent::__construct();$this->setId('rule_id');$this->setDestElementId('edit_form');$this->setTitle(Mage::helper('awardpoints')->__('Segments rules'));}protected function _beforeToHtml(){$this->addTab('main_section', array('label'=>Mage::helper('awardpoints')->__('Rule Information'),'title'=>Mage::helper('awardpoints')->__('Rule Information'),'content'=>$this->getLayout()->createBlock('awardpoints/adminhtml_pointrules_edit_tab_main')->toHtml(),'active'=>true));$this->addTab('conditions_section', array('label'=>Mage::helper('awardpoints')->__('Conditions'),'title'=>Mage::helper('awardpoints')->__('Conditions'),'content'=>$this->getLayout()->createBlock('awardpoints/adminhtml_pointrules_edit_tab_conditions')->toHtml(),));$this->addTab('actions_section', array('label'=>Mage::helper('awardpoints')->__('Actions'),'title'=>Mage::helper('awardpoints')->__('Actions'),'content'=>$this->getLayout()->createBlock('awardpoints/adminhtml_pointrules_edit_tab_actions')->toHtml(),));return parent::_beforeToHtml();}}