<?php
/**
 * Noovias_Cron_Model_Service_Schedule_Disable
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

class Noovias_Cron_Model_Service_Schedule_Disable extends Noovias_Cron_Model_Service_Abstract
{
    const DISABLE_DISABLED = 'disabled';
    const DISABLE_EXCEPTION = 'exception';
    const DISABLE_NOTHING_DONE = 'nothing_done';

    /** @var $serviceDeletePlannedCronjobs Noovias_Cron_Model_Service_Schedule_DeletePlannedCronjobs */
    protected $serviceDeletePlannedCronjobs = null;

    /**
     * @param array $jobCodesToDisable
     * @return array
     */
    public function disableByArray(array $jobCodesToDisable)
    {
        $countDisabledCron = 0;
        $countNotDisabledCron = 0;

        foreach ($jobCodesToDisable as $jobCode) {
            $success = $this->disableByCode($jobCode);
            if($success === self::DISABLE_DISABLED){
                $countDisabledCron++;
            }
            elseif($success === self::DISABLE_EXCEPTION){
                $countNotDisabledCron++;
            }
        }

        return array(
            'success' => $countDisabledCron,
            'error' => $countNotDisabledCron
        );
    }

    /**
     * @param $jobCode
     * @return string
     */
    public function disableByCode($jobCode)
    {
        $success = self::DISABLE_NOTHING_DONE;
        try {
            /** @var $model Noovias_Cron_Model_Schedule_Config */
            $model = $this->getFactory()->getModelScheduleConfig()->load($jobCode, 'job_code');
            //Do nothing if the cron job type is saved in the database and is disabled
            if (!($model->getId() && $model->isStatusDisabled())) {
                $model->setStatusDisabled();
                if (!$model->getId()) {
                    $model->setJobCode($jobCode);
                    $model->setCronExpr($this->helperCron()->getCronExprForJobCode($jobCode));
                }
                $model->save();
                $this->getServiceDeletePlannedCronjobs()->executeByJobCode($jobCode);
                $success = self::DISABLE_DISABLED;
            }
        }
        catch (Exception $e) {
            $success = self::DISABLE_EXCEPTION;
        }
        return $success;
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
}