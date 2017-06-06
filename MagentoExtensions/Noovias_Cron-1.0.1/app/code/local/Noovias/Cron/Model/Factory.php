<?php
/**
 * Noovias_Cron_Model_Factory
 *
 * NOTICE OF LICENSE
 *
 * Noovias_Extensions is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Noovias_Extensions is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Noovias_Extensions. If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Noovias_Extensions to newer
 * versions in the future. If you wish to customize Noovias_Extensions for your
 * needs please refer to http://www.noovias.com for more information.
 *
 * @category    Noovias
 * @package     Noovias_Extensions
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

class Noovias_Cron_Model_Factory
{
    /** @var $helper Noovias_Cron_Helper_Data */
    protected $helper = null;


    /**
     * @param Noovias_Cron_Helper_Data $helper
     */
    public function setHelper(Noovias_Cron_Helper_Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return Noovias_Cron_Helper_Data
     */
    public function helper()
    {
        if($this->helper === null){
            $this->helper = Mage::helper('noovias_cron');
        }
        return $this->helper;
    }

    /**
     * @return Mage_Cron_Model_Schedule
     */
    public function getModelCronSchedule()
    {
        return Mage::getModel('cron/schedule');
    }

    /**
     * @return Mage_Admin_Model_User
     */
    public function getModelAdminUser()
    {
        return Mage::getModel('admin/user');
    }

    /**
     * @return Noovias_Cron_Model_Schedule_Config
     */
    public function getModelScheduleConfig()
    {
        return Mage::getModel('noovias_cron/schedule_config');
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_Disable
     */
    public function getServiceCronDisable()
    {
        return Mage::getModel('noovias_cron/service_schedule_disable');
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_Enable
     */
    public function getServiceCronEnable()
    {
        return Mage::getModel('noovias_cron/service_schedule_enable');
    }

    /**
     * @return Noovias_Cron_Model_Processedjob
     */
    public function getModelProcessedjob()
    {
        return Mage::getModel('noovias_cron/processedjob');
    }

    /**
     * @return Noovias_Cron_Model_Service_Processedjob_Cleanup
     */
    public function getServiceCleanupProcessedjobs()
    {
        return Mage::getModel('noovias_cron/service_processedjob_cleanup');
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_InitCronExpression
     */
    public function getServiceInitCronExpression()
    {
        return Mage::getModel('noovias_cron/service_schedule_initCronExpression');
    }

    /**
     * @return Noovias_Cron_Service_GenerateDataObject
     */
    public function getServiceGenerateDataObject()
    {
        return new Noovias_Cron_Service_GenerateDataObject();
    }

    /**
     * @return Noovias_Cron_Service_GenerateCronExpression
     */
    public function getServiceGenerateCronExpr()
    {
        return new Noovias_Cron_Service_GenerateCronExpression();
    }

    /**
     * @return Noovias_Cron_Service_ValidateCronExpression
     */
    public function getServiceValidateCronExpr()
    {
        return new Noovias_Cron_Service_ValidateCronExpression();
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_DeletePlannedCronjobs
     */
    public function getServiceDeletePlannedCronjobs()
    {
        return Mage::getModel('noovias_cron/service_schedule_deletePlannedCronjobs');
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_InitializeConfig
     */
    public function getServiceInitializeConfig()
    {
        return Mage::getModel('noovias_cron/service_schedule_initializeConfig');
    }
}