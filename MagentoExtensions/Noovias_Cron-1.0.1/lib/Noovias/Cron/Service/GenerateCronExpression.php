<?php
/**
 * Noovias_Cron_Service_GenerateCronExpression
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

class Noovias_Cron_Service_GenerateCronExpression
{
    const GENERATION_EXCEPTION_MESSAGE = 'Cron Expression could not be generated.';
    /**
     * @param Noovias_Cron_Data_CronExpression $data
     * @return string
     * @throws Exception
     */
    public function generateCronExprFromDataObject(Noovias_Cron_Data_CronExpression $data)
    {
        $minutes = $this->calculateMinutes($data);
        $hours = $this->calculateHours($data);
        $dayofmonth = $this->calculateDayOfMonth($data);
        $month = $this->calculateMonth($data);
        $dayofweek = $this->calculateDayOfWeek($data);

        if ($minutes === '' || $hours === '' || $dayofmonth === '' || $month === '' || $dayofweek === '') {
            throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
        }
        $cronExprArray = array($minutes, $hours, $dayofmonth, $month, $dayofweek);
        $cronExpr = implode(' ', $cronExprArray);
        return $cronExpr;
    }

    /**
     * @param Noovias_Cron_Data_CronExpression $data
     * @return string
     * @throws Exception
     */
    protected function calculateMinutes(Noovias_Cron_Data_CronExpression $data)
    {
        $minuteoption = $data->getMinuteOption();
        if($minuteoption === Noovias_Cron_Data_CronExpression::OPTION_EVERY){
            return '*';
        }
        if($minuteoption === Noovias_Cron_Data_CronExpression::OPTION_EVERYX){
            return '*/' . $data->getEveryXMinutes();
        }
        if($minuteoption === Noovias_Cron_Data_CronExpression::OPTION_SPECIFY){
            if (!is_array($data->getSpecifyMinutes())) {
                    throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
                }
            return implode(',', $data->getSpecifyMinutes());
        }
        if($minuteoption === Noovias_Cron_Data_CronExpression::OPTION_FROMTO){
            return $data->getFromMinute() . '-' . $data->getToMinute();
        }
        // Throw exception if there was no return yet
        throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
    }

    /**
     * @param Noovias_Cron_Data_CronExpression $data
     * @return string
     * @throws Exception
     */
    protected function calculateHours(Noovias_Cron_Data_CronExpression $data)
    {
        $houroption = $data->getHourOption();
        if($houroption === Noovias_Cron_Data_CronExpression::OPTION_EVERY){
            return '*';
        }
        if($houroption === Noovias_Cron_Data_CronExpression::OPTION_EVERYX){
            return '*/' . $data->getEveryXHours();
        }
        if($houroption === Noovias_Cron_Data_CronExpression::OPTION_SPECIFY){
            if (!is_array($data->getSpecifyHours())) {
                    throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
                }
            return implode(',', $data->getSpecifyHours());
        }
        if($houroption === Noovias_Cron_Data_CronExpression::OPTION_FROMTO){
            return $data->getFromHour() . '-' . $data->getToHour();
        }
        // Throw exception if there was no return yet
        throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
    }

    /**
     * @param Noovias_Cron_Data_CronExpression $data
     * @return string
     * @throws Exception
     */
    protected function calculateDayOfMonth(Noovias_Cron_Data_CronExpression $data)
    {
        $dayofmonthoption = $data->getDayOfMonthOption();
        if($dayofmonthoption === Noovias_Cron_Data_CronExpression::OPTION_EVERY){
            return '*';
        }
        if($dayofmonthoption === Noovias_Cron_Data_CronExpression::OPTION_SPECIFY)
        {
            if (!is_array($data->getSpecifyDayOfMonth())) {
                    throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
                }
            return implode(',', $data->getSpecifyDayOfMonth());
        }
        if($dayofmonthoption === Noovias_Cron_Data_CronExpression::OPTION_FROMTO){
            return $data->getFromDayOfMonth() . '-' . $data->getToDayOfMonth();
        }
        // Throw exception if there was no return yet
        throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
    }

    /**
     * @param Noovias_Cron_Data_CronExpression $data
     * @return string
     * @throws Exception
     */
    protected function calculateMonth(Noovias_Cron_Data_CronExpression $data)
    {
        $monthoption = $data->getMonthOption();
        if($monthoption === Noovias_Cron_Data_CronExpression::OPTION_EVERY){
            return '*';
        }
        if($monthoption === Noovias_Cron_Data_CronExpression::OPTION_SPECIFY){
            if (!is_array($data->getSpecifyMonth())) {
                    throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
                }
            return implode(',', $data->getSpecifyMonth());
        }
        if($monthoption === Noovias_Cron_Data_CronExpression::OPTION_FROMTO){
            return $data->getFromMonth() . '-' . $data->getToMonth();
        }
        // Throw exception if there was no return yet
        throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
    }

    /**
     * @param Noovias_Cron_Data_CronExpression $data
     * @return string
     * @throws Exception
     */
    protected function calculateDayOfWeek(Noovias_Cron_Data_CronExpression $data)
    {
        $dayofweekoption = $data->getDayOfWeekOption();
        if($dayofweekoption === Noovias_Cron_Data_CronExpression::OPTION_EVERY){
            return '*';
        }
        if($dayofweekoption === Noovias_Cron_Data_CronExpression::OPTION_SPECIFY){
            if (!is_array($data->getSpecifyDayOfWeek())) {
                    throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
                }
            return implode(',', $data->getSpecifyDayOfWeek());
        }
        if($dayofweekoption === Noovias_Cron_Data_CronExpression::OPTION_FROMTO){
            return $data->getFromDayOfWeek() . '-' . $data->getToDayOfWeek();
        }
        // Throw exception if there was no return yet
        throw new Noovias_Cron_Exception_CouldNotGenerateCronExpression(self::GENERATION_EXCEPTION_MESSAGE);
    }
}