<?php
/**
 * Noovias_Cron_Model_Observer_Cron
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

class Noovias_Cron_Model_Observer_Cron extends Mage_Cron_Model_Observer
{
    /** @var $factory Noovias_Cron_Model_Factory */
    protected $factory = null;

    /**
     * Generate jobs for config information
     *
     * @param   $jobs
     * @param   array $exists
     * @return  Noovias_Cron_Model_Cron_Observer
     *
     * @override
     */
    protected function _generateJobs($jobs, $exists)
    {
        $scheduleAheadFor = Mage::getStoreConfig(self::XML_PATH_SCHEDULE_AHEAD_FOR) * 60;
        $schedule = $this->getFactory()->getModelCronSchedule();

        foreach ($jobs as $jobCode => $jobConfig) {
            /**
             * @var $modelConfig Noovias_Cron_Model_Schedule_Config
             */
            $modelConfig = $this->getFactory()->getModelScheduleConfig();

            $modelConfig->load($jobCode, 'job_code');

            /** Do not generate job if the configuration was saved via
             *  Noovias - Cronjobs - Settings and $jobConfig->schedule is
             *  empty (otherwise configurable cronjobs will be generated twice,
             *  as this method is called twice by the generate() method)
             */
            if($modelConfig->getId() && empty($jobConfig->schedule))
            {
                continue;
            }
            /**
             * Do not generate the Cronjob if it is disabled in
             * Noovias - Cronjobs - Settings
             */
            if ($modelConfig->isStatusDisabled()) {
                continue;
            }
            $cronExpr = null;
            /**
             * If the Cron Expression was set in Noovias - Cron - Settings,
             * first use it
             */
            if ($modelConfig->getCronExpr()) {
                $cronExpr = $modelConfig->getCronExpr();
            }
            if (empty($cronExpr) && $jobConfig->schedule->config_path) {
                $cronExpr = Mage::getStoreConfig((string)$jobConfig->schedule->config_path);
            }
            if (empty($cronExpr) && $jobConfig->schedule->cron_expr) {
                $cronExpr = (string)$jobConfig->schedule->cron_expr;
            }
            if (!$cronExpr) {
                continue;
            }

            $now = time();
            $timeAhead = $now + $scheduleAheadFor;
            $schedule->setJobCode($jobCode)
                    ->setCronExpr($cronExpr)
                    ->setStatus(Mage_Cron_Model_Schedule::STATUS_PENDING);

            for ($time = $now; $time < $timeAhead; $time += 60) {
                $ts = strftime('%Y-%m-%d %H:%M:00', $time);
                if (!empty($exists[$jobCode . '/' . $ts])) {
                    // already scheduled
                    continue;
                }
                if (!$schedule->trySchedule($time)) {
                    // time does not match cron expression
                    continue;
                }
                $schedule->unsScheduleId()->save();
            }
        }
        return $this;
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