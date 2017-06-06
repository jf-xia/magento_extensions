<?php
/**
 * Noovias_Cron_Helper_Data
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
class Noovias_Cron_Helper_Data extends Mage_Core_Helper_Data
{
    /** @var $factory Noovias_Cron_Model_Factory */
    protected $factory = null;

    /**
     *
     * @param Mage_Cron_Model_Schedule $schedule
     * @return Varien_Object
     */
    public function getRuntime(Mage_Cron_Model_Schedule $schedule)
    {
        $execTime = $schedule->getExecutedAt();
        $stopTime = $schedule->getFinishedAt();
        if ($execTime == '0000-00-00 00:00:00') {
            $runtime = new Varien_Object();
            $runtime->setIsPending(1);
            $runtime->setHours(0);
            $runtime->setMinutes(0);
            $runtime->setSeconds(0);
            $runtime->setToString('0h 0m 0s');
            return $runtime;
        }

        if ($stopTime == '0000-00-00 00:00:00') {
            $stopTime = now();
        }

        $runtime = strtotime($stopTime) - strtotime($execTime);
        $runtimeSec = $runtime % 60;
        $runtimeMin = (int)($runtime / 60) % 60;
        $runtimeHour = (int)($runtime / 3600);

        $runtime = new Varien_Object();
        $runtime->setIsPending(0);
        $runtime->setHours($runtimeHour);
        $runtime->setMinutes($runtimeMin);
        $runtime->setSeconds($runtimeSec);
        $runtime->setToString($runtimeHour . 'h ' . $runtimeMin . 'm ' . $runtimeSec . 's');
        return $runtime;
    }

    /**
     * @param Mage_Cron_Model_Schedule $schedule
     * @return Varien_Object
     */
    public function getStartingIn(Mage_Cron_Model_Schedule $schedule)
    {
        $schedTime = $schedule->getScheduledAt();

        if ($schedTime == '0000-00-00 00:00:00' or $schedTime == '') {
            $runtime = new Varien_Object();
            $runtime->setHours(0);
            $runtime->setMinutes(0);
            $runtime->setSeconds(0);
            $runtime->setToString('0h 0m 0s');
            return $runtime;
        }

        // Calc Time interval till Exec
        $starttime = strtotime($schedTime) - strtotime(now());
        $prefix = '+';
        if ($starttime < 0) {
            $prefix = '-';
            $starttime *= -1;
        }
        $runtimeSec = $starttime % 60;
        $runtimeMin = (int)($starttime / 60) % 60;
        $runtimeHour = (int)($starttime / 3600);

        $runtime = new Varien_Object();
        $runtime->setHours($runtimeHour);
        $runtime->setMinutes($runtimeMin);
        $runtime->setSeconds($runtimeSec);
        $runtime->setPrefix($prefix);
        $runtime->setToString($runtimeHour . 'h ' . $runtimeMin . 'm ' . $runtimeSec . 's');

        return $runtime;
    }

    /**
     * @return Mage_Core_Model_Config_Element
     */
    public function getAvailableJobCodes()
    {
        return Mage::getConfig()->getNode('crontab/jobs');
    }

    /**
     * @return array
     */
    public function getAvailableJobCodesAsArray()
    {
        $jobCodesArray = array();
        $jobCodes = $this->getAvailableJobCodes();
        foreach ($jobCodes->asArray() as $key => $value) {
            $jobCodesArray[] = $key;
        }
        natcasesort($jobCodesArray);
        return $jobCodesArray;
    }

    /**
     * @param string $jobCode
     * @return string
     *
     * Get the Cron Expression for the job code from the configuration
     * (! not from noovias_cron_schedule_config table)
     */
    public function getCronExprForJobCode($jobCode)
    {
        $cronExpr = '';
        $config = Mage::getConfig()->getNode('crontab/jobs/' . $jobCode);
        if ($config->schedule->config_path) {
            $cronExpr = Mage::getStoreConfig((string)$config->schedule->config_path);
        }
        if ($cronExpr == '' && $config->schedule->cron_expr) {
            $cronExpr = $config->schedule->cron_expr;
        }
        return $cronExpr;
    }

    /**
     * @return array
     */
    public function getCronMonthArray()
    {
        return Noovias_Cron_Data_CronExpression_Settings_Month::getCronMonthArray();
    }

    /**
     * @return array
     */
    public function getCronDayArray()
    {
        return Noovias_Cron_Data_CronExpression_Settings_Day::getCronDayArray();
    }

    /**
     * @return array
     */
    public function getEachMinuteArray()
    {
        return Noovias_Cron_Data_CronExpression_Settings_Minute::getEachMinuteArray();
    }

    public function getEachHourArray()
    {
        return Noovias_Cron_Data_CronExpression_Settings_Hour::getEachHourArray();
    }

    /**
     * @return int
     */
    public function getCurrentAdminUserId()
    {
        /** @var $session Mage_Admin_Model_Session */
        $session = Mage::getSingleton('admin/session');
        /** @var $adminUser Mage_Admin_Model_User */
        $adminUser = $session->getUser();
        return $adminUser->getId();
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
            $this->factory = Mage::getModel('noovias_cron/factory');
        }
        return $this->factory;
    }
}