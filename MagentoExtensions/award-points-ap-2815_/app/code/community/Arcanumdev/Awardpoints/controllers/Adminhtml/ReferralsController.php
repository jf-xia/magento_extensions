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
 class Arcanumdev_Awardpoints_Adminhtml_ReferralsController extends Mage_Adminhtml_Controller_action{protected function _initAction(){$this->loadLayout()->_setActiveMenu('awardpoints/referrals')->_addBreadcrumb(Mage::helper('awardpoints')->__('Referrals'), Mage::helper('awardpoints')->__('Referrals'));return $this;}public function indexAction(){$this->_initAction()->_addContent($this->getLayout()->createBlock('awardpoints/adminhtml_referrals'))->renderLayout();}public function editAction(){$id=$this->getRequest()->getParam('id');$model =Mage::getModel('awardpoints/referral')->load($id);if($model->getId() || $id == 0){$data=Mage::getSingleton('adminhtml/session')->getFormData(true);if(!empty($data)){$model->setData($data);}Mage::register('stats_data', $model);$this->loadLayout();$this->_setActiveMenu('awardpoints/referrals');$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);$this->_addContent($this->getLayout()->createBlock('awardpoints/adminhtml_referrals_edit'));$this->renderLayout();}else{Mage::getSingleton('adminhtml/session')->addError(Mage::helper('awardpoints')->__('No referral'));$this->_redirect('*/*/');}}public function newAction(){$this->_forward('edit');}  public function saveAction(){if($data=$this->getRequest()->getPost()){$model=Mage::getModel('awardpoints/referral');$model->setData($data)->setId($this->getRequest()->getParam('id'));try{$model->save();Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('awardpoints')->__('Points were successfully saved'));Mage::getSingleton('adminhtml/session')->setFormData(false);if($this->getRequest()->getParam('back')){$this->_redirect('*/*/edit', array('id'=>$model->getId()));return;}$this->_redirect('*/*/');return;}catch (Exception $e){Mage::getSingleton('adminhtml/session')->addError($e->getMessage());Mage::getSingleton('adminhtml/session')->setFormData($data);$this->_redirect('*/*/edit', array('id'=>$this->getRequest()->getParam('id')));return;}}Mage::getSingleton('adminhtml/session')->addError(Mage::helper('awardpoints')->__('Unable to save'));$this->_redirect('*/*/');}public function deleteAction(){if( $this->getRequest()->getParam('id') > 0 ){try{$model=Mage::getModel('awardpoints/referral');$model->setId($this->getRequest()->getParam('id'))->delete();Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('awardpoints')->__('Referral were successfully deleted'));$this->_redirect('*/*/');}catch (Exception $e){Mage::getSingleton('adminhtml/session')->addError($e->getMessage());$this->_redirect('*/*/edit', array('id'=>$this->getRequest()->getParam('id')));}}$this->_redirect('*/*/');}public function massDeleteAction(){$ruleIds=$this->getRequest()->getParam('awardpoints_referral_ids');if(!is_array($ruleIds)){Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select referrals'));}else{try{foreach ($ruleIds as $ruleId){$rule=Mage::getModel('awardpoints/referral')->load($ruleId);$rule->delete();}Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('awardpoints')->__('Total of %d points were successfully deleted', count($ruleIds)));}catch (Exception $e){Mage::getSingleton('adminhtml/session')->addError($e->getMessage());}}$this->_redirect('*/*/index');}}