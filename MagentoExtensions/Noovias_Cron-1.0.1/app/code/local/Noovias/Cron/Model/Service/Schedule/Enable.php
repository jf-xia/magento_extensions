<?php
/**
 * Noovias_Cron_Model_Service_Schedule_Enable
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

class Noovias_Cron_Model_Service_Schedule_Enable extends Noovias_Cron_Model_Service_Abstract
{
    const ENABLE_ENABLED = 'enabled';
    const ENABLE_EXCEPTION = 'exception';
    const ENABLE_NOTHING_DONE = 'nothing_done';

    public function enableByArray(array $jobCodesToEnable)
    {
        $countEnabledCron = 0;
        $countNotEnabledCron = 0;
        foreach ($jobCodesToEnable as $jobCode) {
            $success = $this->enableByCode($jobCode);
            if($success === self::ENABLE_ENABLED){
                $countEnabledCron++;
            }
            elseif($success === self::ENABLE_EXCEPTION){
                $countNotEnabledCron++;
            }
        }

        return array(
            'success' => $countEnabledCron,
            'error' => $countNotEnabledCron
        );
    }

    /**
     * @param $jobCode
     * @return string
     */
    public function enableByCode($jobCode)
    {
        $success = self::ENABLE_NOTHING_DONE;
        try {
            /** @var $model Noovias_Cron_Model_Schedule_Config */
            $model = $this->getFactory()->getModelScheduleConfig()->load($jobCode, 'job_code');
            if ($model->getId() && $model->isStatusDisabled()) {
                //There is already a database entry for this cron job type
                //otherwise do nothing: the cronjob type is enabled by default
                $model->setStatusEnabled();
                $model->save();
                $success = self::ENABLE_ENABLED;
            }
        }
        catch (Exception $e) {
            $success = self::ENABLE_EXCEPTION;
        }
        return $success;
    }
}