<?php
/**
 * Noovias_Cron_Adminhtml_SettingsController
 *
 * NOTICE OF LICENSE
 *
 * Noovias_Cron is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Noovias_Cron is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Noovias_Cron. If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Noovias_Cron to newer
 * versions in the future. If you wish to customize Noovias_Cron for your
 * needs please refer to http://www.noovias.com for more information.
 *
 * @category    Noovias
 * @package        Noovias_Cron
 * @copyright   Copyright (c) 2012 <info@noovias.com> - noovias.com
 * @license     <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link        http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Cron
 * @copyright      Copyright (c) 2012 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author      noovias.com - Core Team <info@noovias.com>
 */

class Noovias_Cron_Adminhtml_SettingsController extends Mage_Adminhtml_Controller_Action
{
    /** @var $factory Noovias_Cron_Model_Factory */
    protected $factory = null;
    protected $helperCron = null;

    protected $serviceDisable = null;
    protected $serviceEnable = null;
    /** @var $serviceDeletePlannedCronjobs Noovias_Cron_Model_Service_Schedule_DeletePlannedCronjobs */
    protected $serviceDeletePlannedCronjobs = null;
    /** @var  $serviceInitConfig Noovias_Cron_Model_Service_Schedule_InitializeConfig*/
    protected $serviceInitConfig = null;


    /**
     * @return Noovias_Cron_Model_Service_Schedule_Disable
     */
    protected function getServiceDisable()
    {
        if ($this->serviceDisable === null) {
            $this->serviceDisable = $this->getFactory()->getServiceCronDisable();
        }
        return $this->serviceDisable;
    }

    /**
     * @param Noovias_Cron_Model_Service_Schedule_Disable $serviceDisable
     */
    public function setServiceDisable(Noovias_Cron_Model_Service_Schedule_Disable $serviceDisable)
    {
        $this->serviceDisable = $serviceDisable;
    }

    /**
     * @param Noovias_Cron_Model_Service_Schedule_Enable $serviceEnable
     */
    public function setServiceEnable(Noovias_Cron_Model_Service_Schedule_Enable $serviceEnable)
    {
        $this->serviceEnable = $serviceEnable;
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_Enable
     */
    protected function getServiceEnable()
    {
        if ($this->serviceEnable === null) {
            $this->serviceEnable = $this->getFactory()->getServiceCronEnable();
        }
        return $this->serviceEnable;
    }


    /**
     * Init layout, menu and breadcrumb
     *
     * @return Noovias_Cron_Adminhtml_SettingsController
     */
    protected function _initAction()
    {
        $this->_title($this->helperCron()->__('System'))
                ->_title($this->helperCron()->__('Noovias - Cronjobs'))
                ->_title($this->helperCron()->__('Settings'));

        $this->loadLayout()
                ->_setActiveMenu('system/noovias_cron/settings')
                ->_addBreadcrumb($this->__('System'), $this->__('System'))
                ->_addBreadcrumb($this->__('Settings'), $this->__('Settings'));

        return $this;
    }


    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Grid
     */
    public function gridAction()
    {
        $this->loadLayout();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('noovias_cron/adminhtml_settings_grid')->toHtml()
        );
    }

    public function editAction()
    {
        if (!$this->getRequest()->getParam('job_code')) {
            Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl() . '/admin');
            return;
        }
        if (!in_array($this->getRequest()->getParam('job_code'), $this->helperCron()->getAvailableJobCodesAsArray())) {
            Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl() . '/admin');
            return;
        }

        $jobCode = (string)$this->getRequest()->getParam('job_code');
        $config = $this->getServiceInitConfig()->initByJobCode($jobCode);

        if (!$config->isDataObjectSet()) {
            $this->_getSession()->addNotice($this->helperCron()
                    ->__('The saved cron expression is not presentable in the GUI, mode is set to expert.'));
        }

        Mage::register('noovias_cron_schedule_config', $config);

        $this->_initAction();
        $this->_title($this->helperCron()->__('Edit Cronjob'));
        $this->renderLayout();
    }

    /**
     *
     */
    public function saveAction()
    {
        $jobCode = (string)$this->getRequest()->getParam('job_code');
        if (!$jobCode) {
            $this->_getSession()->addError($this->helperCron()->__('An Error Occured'));
            $this->_redirect('*/*/');
            return;
        }
        /** @var $config Noovias_Cron_Model_Schedule_Config */
        $config = $this->getFactory()->getModelScheduleConfig()->load($jobCode, 'job_code');

        $redirectBack = false;

        try {
            /** Cron Expression handling: */
            $request = $this->getRequest();

            $config->initByRequest($request);

            $config->save();

            //Deleting planned jobs in case of deactivation
            if ($config->isStatusDisabled()) {
                $this->getServiceDeletePlannedCronjobs()->executeByJobCode($jobCode);
            }

            $this->_getSession()->addSuccess(
                $this->__('The Configuration for this Cronjob has been successfully saved.')
            );
        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $redirectBack = true;
        }
        catch (Exception $e) {
            $this->_getSession()->addException($e, $e->getMessage());
            $redirectBack = true;
        }

        if ($redirectBack) {
            $this->_redirect('*/*/edit', array('job_code' => $jobCode, '_current' => true));
        }
        else {
            $this->_redirect('*/*/');
        }
    }

    /**
     * Enable selected Cronjobs
     */
    public function massEnableAction()
    {
        $jobCodesToEnable = $this->getRequest()->getPost('cronjob_codes', array());

        $result = $this->getServiceEnable()->enableByArray($jobCodesToEnable);

        if ($result['error']) {
            $this->_getSession()->addError($this->__('%s Cronjob(s) could not be enabled.', $result['error']));
        }
        if ($result['success']) {
            $this->_getSession()->addSuccess($this->__('%s Cronjob(s) have been enabled.', $result['success']));
        }
        $this->_redirect('*/*/');
    }

    /**
     * Disable selected Cronjobs
     */
    public function massDisableAction()
    {
        $jobCodesToDisable = $this->getRequest()->getPost('cronjob_codes', array());

        $result = $this->getServiceDisable()->disableByArray($jobCodesToDisable);

        if ($result['error']) {
            $this->_getSession()->addError($this->__('%s Cronjob(s) could not be disabled.', $result['error']));
        }
        if ($result['success']) {
            $this->_getSession()->addSuccess($this->__('%s Cronjob(s) have been disabled.', $result['success']));
        }
        $this->_redirect('*/*/');
    }

    /**
     * @return Noovias_Cron_Helper_Data
     */
    protected function helperCron()
    {
        if ($this->helperCron === null) {
            $this->helperCron = Mage::helper('noovias_cron');
        }
        return $this->helperCron;
    }

    /**
     * @param Noovias_Cron_Helper_Data $helperCron
     */
    public function setHelperCron(Noovias_Cron_Helper_Data $helperCron)
    {
        $this->helperCron = $helperCron;
    }

    /**
     * @param Noovias_Cron_Model_Factory $factory
     */
    public function setFactory(Noovias_Cron_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Noovias_Cron_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->helperCron()->getFactory();
        }
        return $this->factory;
    }

    /**
     * @param Noovias_Cron_Model_Service_Schedule_DeletePlannedCronjobs $serviceDeletePlannedCronjobs
     */
    public function setServiceDeletePlannedCronjobs(Noovias_Cron_Model_Service_Schedule_DeletePlannedCronjobs $serviceDeletePlannedCronjobs)
    {
        $this->serviceDeletePlannedCronjobs = $serviceDeletePlannedCronjobs;
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_DeletePlannedCronjobs
     */
    public function getServiceDeletePlannedCronjobs()
    {
        if($this->serviceDeletePlannedCronjobs === null){
            $this->serviceDeletePlannedCronjobs = $this->getFactory()->getServiceDeletePlannedCronjobs();
        }
        return $this->serviceDeletePlannedCronjobs;
    }

    /**
     * @param Noovias_Cron_Model_Service_Schedule_InitializeConfig $serviceInitConfig
     */
    public function setServiceInitConfig(Noovias_Cron_Model_Service_Schedule_InitializeConfig $serviceInitConfig)
    {
        $this->serviceInitConfig = $serviceInitConfig;
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_InitializeConfig
     */
    protected function getServiceInitConfig()
    {
        if($this->serviceInitConfig === null){
            $this->serviceInitConfig = $this->getFactory()->getServiceInitializeConfig();
        }
        return $this->serviceInitConfig;
    }
}