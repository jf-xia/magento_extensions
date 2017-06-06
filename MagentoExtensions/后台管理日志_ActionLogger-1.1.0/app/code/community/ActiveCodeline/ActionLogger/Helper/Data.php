<?php
/**
 * ActiveCodeline_ActionLogger_Helper_Data
 *
 * @category    ActiveCodeline
 * @package     ActiveCodeline_ActionLogger
 * @author      Branko Ajzele (http://activecodeline.net)
 * @copyright   Copyright (c) Branko Ajzele
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ActiveCodeline_ActionLogger_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function _canLogRequestParams()
    {
        $storeConfig = Mage::getStoreConfig('dev');
        $logRequestParams = false;

        if (isset($storeConfig['activecodeline_actionlogger']['log_request_params'])) {
            if ($storeConfig['activecodeline_actionlogger']['log_request_params'] == "1") {
                $logRequestParams = true;
            }
        }

        return $logRequestParams;
    }

    public function _canLogFrontendActions()
    {
        $storeConfig = Mage::getStoreConfig('dev');
        $logFrontendActions = false;

        if (isset($storeConfig['activecodeline_actionlogger']['log_frontend_actions'])) {
            if ($storeConfig['activecodeline_actionlogger']['log_frontend_actions'] == "1") {
                $logFrontendActions = true;
            }
        }

        return $logFrontendActions;
    }

    public function _canLogAdminActions()
    {
        $storeConfig = Mage::getStoreConfig('dev');
        $logAdminActions = false;

        if (isset($storeConfig['activecodeline_actionlogger']['log_admin_actions'])) {
            if ($storeConfig['activecodeline_actionlogger']['log_admin_actions'] == "1") {
                $logAdminActions = true;
            }
        }

        return $logAdminActions;
    }

    public function _canViewLoggedRequestParams()
    {
        $storeConfig = Mage::getStoreConfig('dev');
        $viewLoggedRequestParams = false;

        if (isset($storeConfig['activecodeline_actionlogger']['view_logged_request_params'])) {
            if ($storeConfig['activecodeline_actionlogger']['view_logged_request_params'] == "1") {
                $viewLoggedRequestParams = true;
            }
        }

        return $viewLoggedRequestParams;
    }
}
