<?php
/**
 * Noovias_Cron_Adminhtml_ScheduleController
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
 * @copyright   Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license     <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link        http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Cron
 * @copyright      Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author      noovias.com - Core Team <info@noovias.com>
 */
class Noovias_Cron_Adminhtml_ScheduleController extends Mage_Adminhtml_Controller_Action
{
    /** @var $factory Noovias_Cron_Model_Factory */
    protected $factory = null;
    /**
     *
     * @return Mage_Cron_Model_Schedule
     */
    protected function _initSchedule()
    {
        $scheduleId = (int)$this->getRequest()->getParam('schedule_id');

        $schedule = $this->getFactory()->getModelCronSchedule();

        if ($scheduleId) {
            $schedule->load($scheduleId);
        }

        Mage::register('noovias_cron_schedule', $schedule);

        return $schedule;
    }

    /**
     * Init layout, menu and breadcrumb
     *
     * @return Noovias_Cron_Adminhtml_ScheduleController
     */
    protected function _initAction()
    {
        $this->loadLayout()
                ->_setActiveMenu('system/noovias_cron/schedule')
                ->_addBreadcrumb($this->__('System'), $this->__('System'))
                ->_addBreadcrumb($this->__('Schedule'), $this->__('Schedule'));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->helperCron()->__('System'))
             ->_title($this->helperCron()->__('Noovias - Cronjobs'))
             ->_title($this->helperCron()->__('Schedule'));

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
            $this->getLayout()->createBlock('noovias_cron/adminhtml_schedule_grid')->toHtml()
        );
    }

    /**
     * Create new Schedule
     */
    public function newAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Schedule edit form
     */
    public function editAction()
    {
        $this->_title($this->helperCron()->__('System'))
             ->_title($this->helperCron()->__('Noovias - Cronjobs'))
             ->_title($this->helperCron()->__('Schedule'))
             ->_title($this->helperCron()->__('Edit Schedule'));

        $schedule = $this->_initSchedule();
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Schedule save action
     */
    public function saveAction()
    {
        $schedule = $this->_initSchedule();
        $this->_initAction();

        $redirectBack = false;
        try {
            $params = $this->getRequest()->getParams();
            $schedule->addData($params);

            // Save datetime for the correct timezone
            $scheduledAt = $this->helperDate()->convertToGmt($params['scheduled_at']); // inelegant, but working

            $schedule->setStatus(Mage_Cron_Model_Schedule::STATUS_PENDING);
            $schedule->setScheduledAt($scheduledAt->format('Y-m-d H:i:s'));

            if (!$schedule->getId()) {
                $schedule->unsScheduleId();
                $schedule->setCreatedAt(strftime('%Y-%m-%d %H:%M:%S', time()));
            }

            $schedule->save();

            $this->_getSession()->addSuccess($this->__('Schedule was successfully saved.'));
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
            $this->_redirect('*/*/edit', array(
                'id' => $this->getRequest()->getParam('schedule_id'),
                '_current' => true
            ));
        }
        else {
            $this->_redirect('*/*/');
        }
    }


    /**
     * @return void
     */
    public function deleteAction()
    {
        $this->_initAction();
        $schedule = $this->_initSchedule();

        try {
            $schedule->delete();
            $this->_getSession()->addSuccess($this->__('Schedule deleted'));
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->getResponse()->setRedirect($this->getUrl('*/*/'));
    }

    /**
     * Cancel selected orders
     */
    public function massDeleteAction()
    {
        $cronIds = $this->getRequest()->getPost('cron_ids', array());
        $countDeleteCron = 0;
        $countNonDeleteCron = 0;
        foreach ($cronIds as $cronId) {
            try {
                $cron = $this->getFactory()->getModelCronSchedule()->load($cronId);
                $cron->delete();
                $countDeleteCron++;
            }
            catch (Exception $e) {
                $countNonDeleteCron++;
            }
        }
        if ($countNonDeleteCron) {
            if ($countDeleteCron) {
                $this->_getSession()->addError($this->__('%s Cronjob(s) cannot be deleted', $countNonDeleteCron));
            }
            else {
                $this->_getSession()->addError($this->__('The Cronjob(s) cannot be deleted'));
            }
        }
        if ($countDeleteCron) {
            $this->_getSession()->addSuccess($this->__('%s Cronjob(s) have been deleted.', $countDeleteCron));
        }
        $this->_redirect('*/*/');
    }


    /**
     * @return Noovias_Extensions_Helper_Date
     */
    protected function helperDate()
    {
        return Mage::helper('noovias_extensions/date');
    }

    /**
     * @return Noovias_Cron_Helper_Data
     */
    protected function helperCron()
    {
        return Mage::helper('noovias_cron');
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
    protected function getFactory()
    {
        if($this->factory === null){
            $this->factory = $this->helperCron()->getFactory();
        }
        return $this->factory;
    }
}