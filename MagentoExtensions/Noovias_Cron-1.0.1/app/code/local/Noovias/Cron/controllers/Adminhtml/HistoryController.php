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
class Noovias_Cron_Adminhtml_HistoryController extends Mage_Adminhtml_Controller_Action
{
    /** @var Noovias_Cron_Model_Factory $factory */
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

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->helperCron()->__('System'))
             ->_title($this->helperCron()->__('Noovias - Cronjobs'))
             ->_title($this->helperCron()->__('History'));
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * @return void
     */
    public function gridAction()
    {
        $this->loadLayout();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('noovias_cron/adminhtml_history_grid')->toHtml()
        );
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