<?php
/**
 * ActiveCodeline_ActionLogger_Model_Observer
 *
 * @category    ActiveCodeline
 * @package     ActiveCodeline_ActionLogger
 * @author      Branko Ajzele (http://activecodeline.net)
 * @copyright   Copyright (c) Branko Ajzele
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ActiveCodeline_ActionLogger_Model_Observer
{


	public function hookToAdminhtmlControllerActionPredispatch($observer = null)
	{
        if (!Mage::helper('activecodeline_actionlogger')->_canLogAdminActions()) {
            return;
        }

        $log = Mage::getModel('activecodeline_actionlogger/admin');

        if ('actionlogger_admin_grid' != Mage::app()->getRequest()->getControllerName()
                && 'actionlogger_frontend_grid' != Mage::app()->getRequest()->getControllerName())
        {
            $log->setActionName(Mage::app()->getRequest()->getActionName());
            $log->setControllerName(Mage::app()->getRequest()->getControllerName());

            if (Mage::helper('activecodeline_actionlogger')->_canLogRequestParams()) {
                if($params = Mage::app()->getRequest()->getParams()) {
                    $log->setParams(Mage::helper('core')->encrypt(serialize($params)));
                }
            }

            $log->setClientIp(Mage::app()->getRequest()->getClientIp());
            $log->setControllerModule(Mage::app()->getRequest()->getControllerModule());

            if ($user = Mage::getSingleton('admin/session')->getUser()) {
                $log->setUserId($user->getId());
                $log->setUsername($user->getUsername());
            } else {
                $log->setUserId(0);
                $log->setUsername('Unknown');
            }

            try {
                $log->save();
            } catch (Exception $e) {
                Mage::log('file: '.__FILE__.', line: '.__LINE__, 'msg: '.$e->getMessage());
            }
        }
    }

	public function hookToFrontendControllerActionPredispatch($observer = null)
	{
        if (!Mage::helper('activecodeline_actionlogger')->_canLogFrontendActions()) {
            return;
        }

        $log = Mage::getModel('activecodeline_actionlogger/frontend');

        $log->setActionName(Mage::app()->getRequest()->getActionName());
        $log->setControllerName(Mage::app()->getRequest()->getControllerName());

        if (Mage::helper('activecodeline_actionlogger')->_canLogRequestParams()) {
            if($params = Mage::app()->getRequest()->getParams()) {
                $log->setParams(Mage::helper('core')->encrypt(serialize($params)));
            }
        }

        $log->setClientIp(Mage::app()->getRequest()->getClientIp());
        $log->setControllerModule(Mage::app()->getRequest()->getControllerModule());
        
        if ($customer = Mage::getSingleton('customer/session')->getCustomer()) {
            $log->setCustomerId($customer->getId());
        } else {
            $log->setCustomerId(0);
        }

        try {
            $log->save();
        } catch (Exception $e) {
            Mage::log('file: '.__FILE__.', line: '.__LINE__, 'msg: '.$e->getMessage());
        }
    }
}
