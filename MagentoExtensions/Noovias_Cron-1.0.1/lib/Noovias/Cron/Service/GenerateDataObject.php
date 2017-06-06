<?php
/**
 * Noovias_Cron_Service_GenerateDataObject
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

class Noovias_Cron_Service_GenerateDataObject
{
    /**
     * @param $cronExpr string
     * @return Noovias_Cron_Data_CronExpression
     * @throws Noovias_Cron_Exception_CouldNotGenerateDataObject
     */
    public function generateDataObjectFromCronExpr($cronExpr)
    {
        try {
            $data = new Noovias_Cron_Data_CronExpression();

            $cronExprArray = explode(' ', $cronExpr);

            $cronMinutes = $cronExprArray[0];
            $this->convertMinutes($cronMinutes, $data);

            $cronHours = $cronExprArray[1];
            $this->convertHours($cronHours, $data);

            $cronDayOfMonth = $cronExprArray[2];
            $this->convertDayOfMonth($cronDayOfMonth, $data);

            $cronMonth = $cronExprArray[3];
            $this->convertMonth($cronMonth, $data);

            //convert day of week
            $cronDayOfWeek = $cronExprArray[4];
            $this->convertDayOfWeek($cronDayOfWeek, $data);

            return $data;
        }
        catch (Exception $e) {
            throw new Noovias_Cron_Exception_CouldNotGenerateDataObject('Cron expression \'' . $cronExpr . '\' could not be parsed.
            Message: ' . $e->getMessage());
        }
    }

    /**
     * @param string $cronMinutes
     * @param Noovias_Cron_Data_CronExpression $data
     */
    protected function convertMinutes($cronMinutes, Noovias_Cron_Data_CronExpression &$data)
    {
        /**
         *  if cron minutes contain slash (e.g. *{slash}15), set option to everyx
         *  and set everyxminutes to the number after *{slash}
         */
        if (strpos($cronMinutes, '/') !== false) {
            $data->setMinuteOption(Noovias_Cron_Data_CronExpression::OPTION_EVERYX);
            preg_match('#\*/(\d+)#', $cronMinutes, $pockets);
            if(array_key_exists(1, $pockets)){
                $data->setEveryXMinutes($pockets[1]);
            }
            else{
                throw new Exception('Every x minutes parameter wrong.');
            }
        }
        /**
         * if cron minutes contain comma, set option to specify and explode the
         * values to an array and set it for specifyminutes
         *
         */
        elseif (strpos($cronMinutes, ',') !== false) {
            $data->setMinuteOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyMinutes(explode(',', $cronMinutes));
        }
        /**
         * cron minutes contain '-' ---> set option to fromto and set the fromminute and tominute
         * values
         */
        elseif (strpos($cronMinutes, '-') !== false) {
            $data->setMinuteOption(Noovias_Cron_Data_CronExpression::OPTION_FROMTO);
            preg_match('#(\d+)-(\d+)#', $cronMinutes, $pockets);
            if(array_key_exists(1, $pockets) && array_key_exists(2, $pockets)){
                $data->setFromMinute($pockets[1]);
                $data->setToMinute($pockets[2]);
            }
            else{
                throw new Exception('From minute or to minute parameter wrong.');
            }

        }
        elseif ($cronMinutes === '*') {
            $data->setMinuteOption(Noovias_Cron_Data_CronExpression::OPTION_EVERY);
        }
        /**
         * if only one value is specified, make an array of it
         */
        elseif (is_numeric($cronMinutes)) {
            $data->setMinuteOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyMinutes(array($cronMinutes));
        }
    }

    /**
     * @param string $cronHours
     * @param Noovias_Cron_Data_CronExpression $data
     */
    protected function convertHours($cronHours, Noovias_Cron_Data_CronExpression &$data)
    {
        if (strpos($cronHours, '/') !== false) {
            $data->setHourOption(Noovias_Cron_Data_CronExpression::OPTION_EVERYX);
            preg_match('#\*/(\d+)#', $cronHours, $pockets);
            if(array_key_exists(1, $pockets)){
                $data->setEveryXHours($pockets[1]);
            }
            else{
                throw new Exception('Every x hours parameter wrong.');
            }
        }
        elseif (strpos($cronHours, ',') !== false) {
            $data->setHourOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyHours(explode(',', $cronHours));
        }
        elseif (strpos($cronHours, '-') !== false) {
            $data->setHourOption(Noovias_Cron_Data_CronExpression::OPTION_FROMTO);
            preg_match('#(\d+)-(\d+)#', $cronHours, $pockets);
            if(array_key_exists(1, $pockets) && array_key_exists(2, $pockets)){
                $data->setFromHour($pockets[1]);
                $data->setToHour($pockets[2]);
            }
            else{
                throw new Exception('From hour or to hour parameter wrong.');
            }

        }
        elseif ($cronHours === '*') {
            $data->setHourOption(Noovias_Cron_Data_CronExpression::OPTION_EVERY);
        }
        elseif (is_numeric($cronHours)) {
            $data->setHourOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyHours(array($cronHours));
        }
    }

    /**
     * @param string $cronDayOfMonth
     * @param Noovias_Cron_Data_CronExpression $data
     */
    protected function convertDayOfMonth($cronDayOfMonth, Noovias_Cron_Data_CronExpression &$data)
    {
        if (strpos($cronDayOfMonth, ',') !== false) {
            $data->setDayOfMonthOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyDayOfMonth(explode(',', $cronDayOfMonth));
        }
        elseif (strpos($cronDayOfMonth, '-') !== false) {
            $data->setDayOfMonthOption(Noovias_Cron_Data_CronExpression::OPTION_FROMTO);
            preg_match('#(\d+)-(\d+)#', $cronDayOfMonth, $pockets);
            if(array_key_exists(1, $pockets) && array_key_exists(2, $pockets)){
                $data->setFromDayOfMonth($pockets[1]);
                $data->setToDayOfMonth($pockets[2]);
            }
            else{
                throw new Exception('From or to day of month parameter wrong.');
            }
        }
        elseif ($cronDayOfMonth === '*') {
            $data->setDayOfMonthOption(Noovias_Cron_Data_CronExpression::OPTION_EVERY);
        }
        elseif (is_numeric($cronDayOfMonth)) {
            $data->setDayOfMonthOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyDayOfMonth(array($cronDayOfMonth));
        }
    }

    /**
     * @param string $cronMonth
     * @param Noovias_Cron_Data_CronExpression $data
     */
    protected function convertMonth($cronMonth, Noovias_Cron_Data_CronExpression &$data)
    {
        if (strpos($cronMonth, ',') !== false) {
            $data->setMonthOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyMonth(explode(',', $cronMonth));
        }
        elseif (strpos($cronMonth, '-') !== false) {
            $data->setMonthOption(Noovias_Cron_Data_CronExpression::OPTION_FROMTO);
            preg_match('#(\w+)-(\w+)#', $cronMonth, $pockets);
            if(array_key_exists(1, $pockets) && array_key_exists(2, $pockets)){
                $data->setFromMonth($pockets[1]);
                $data->setToMonth($pockets[2]);
            }
            else{
                throw new Exception('From month or to month parameter wrong.');
            }
        }
        elseif ($cronMonth === '*') {
            $data->setMonthOption(Noovias_Cron_Data_CronExpression::OPTION_EVERY);
        }
        else {
            $data->setMonthOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyMonth(array($cronMonth));
        }
    }

    /**
     * @param string $cronDayOfWeek
     * @param Noovias_Cron_Data_CronExpression $data
     */
    protected function convertDayOfWeek($cronDayOfWeek, Noovias_Cron_Data_CronExpression &$data)
    {
        if (strpos($cronDayOfWeek, ',') !== false) {
            $data->setDayOfWeekOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyDayOfWeek(explode(',', $cronDayOfWeek));
        }
        elseif (strpos($cronDayOfWeek, '-') !== false) {
            $data->setDayOfWeekOption(Noovias_Cron_Data_CronExpression::OPTION_FROMTO);
            preg_match('#(\w+)-(\w+)#', $cronDayOfWeek, $pockets);
            if(array_key_exists(1, $pockets) && array_key_exists(2, $pockets)){
                $data->setFromDayOfWeek($pockets[1]);
                $data->setToDayOfWeek($pockets[2]);
            }
            else{
                throw new Exception('From or to day of week parameter wrong.');
            }
        }
        elseif ($cronDayOfWeek === '*') {
            $data->setDayOfWeekOption(Noovias_Cron_Data_CronExpression::OPTION_EVERY);
        }
        else {
            $data->setDayOfWeekOption(Noovias_Cron_Data_CronExpression::OPTION_SPECIFY);
            $data->setSpecifyDayOfWeek(array($cronDayOfWeek));
        }
    }
}