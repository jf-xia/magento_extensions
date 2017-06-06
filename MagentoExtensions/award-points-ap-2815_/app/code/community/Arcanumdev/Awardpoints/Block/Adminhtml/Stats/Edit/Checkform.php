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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Stats_Edit_Checkform extends Mage_Adminhtml_Block_Widget_Form{protected function _prepareForm(){$rule=Mage::getModel('awardpoints/stats');$form=new Varien_Data_Form(array('id'=>'edit_form','action'=>$this->getData('action'),'method'=>'post'));$fieldset=$form->addFieldset('order_details',array('legend'=>Mage::helper('awardpoints')->__('Check Order Points')));$fieldset->addField('from', 'date',array('name'=>'from','title'=>Mage::helper('awardpoints')->__('From Date'),'label'=>Mage::helper('awardpoints')->__('From Date'),'image'=>Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'/adminhtml/default/default/images/grid-cal.gif','format'=>Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),'required'=>true,));$fieldset->addField('ends', 'date',array('name'=>'ends','title'=>Mage::helper('awardpoints')->__('To Date'),'label'=>Mage::helper('awardpoints')->__('To Date'),'image'=>Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'/adminhtml/default/default/images/grid-cal.gif','format'=>Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),'required'=>true,));if ( Mage::getSingleton('adminhtml/session')->getStatsData() ){$form->setValues(Mage::getSingleton('adminhtml/session')->getStatsData());Mage::getSingleton('adminhtml/session')->setStatsData(null);} elseif ( Mage::registry('stats_data') ) {$form->setValues(Mage::registry('stats_data')->getData());}$form->setUseContainer(true);$this->setForm($form);return parent::_prepareForm();}}