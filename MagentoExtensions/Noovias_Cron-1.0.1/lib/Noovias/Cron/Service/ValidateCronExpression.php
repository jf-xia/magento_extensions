<?php
/**
 * Noovias_Cron_Service_ValidateCronExpression
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

class Noovias_Cron_Service_ValidateCronExpression
{

    /**
     * @param Noovias_Cron_Data_CronExpression $data
     * @return bool
     */
    public function isCronExpressionPresentableByObject(Noovias_Cron_Data_CronExpression $data)
    {
        $generationService = new Noovias_Cron_Service_GenerateCronExpression();
        $cronExpr = $generationService->generateCronExprFromDataObject($data);
        return $this->isCronExpressionPresentable($cronExpr);
    }

    /**
     * Validate whether the cron expression is suitable to show in the edit form drop downs
     *
     * @param $cronExpr string
     * @return bool
     */
    public function isCronExpressionPresentable($cronExpr)
    {
        try {
            $cronExprArray = explode(' ', $cronExpr);
            if (count($cronExprArray) != 5) {
                return false;
            }

            foreach ($cronExprArray as $value) {
                if ($value === '') {
                    return false;
                }
            }

            $return = $this->isMinutesPresentable($cronExprArray[0]);
            if(!$return){
                return false;
            }

            $return = $this->isHoursPresentable($cronExprArray[1]);
            if(!$return){
                return false;
            }

            $return = $this->isDayOfMonthPresentable($cronExprArray[2]);
            if(!$return){
                return false;
            }

            $return = $this->isMonthPresentable($cronExprArray[3]);
            if(!$return){
                return false;
            }

            $return = $this->isDayOfWeekPresentable($cronExprArray[4]);
            if(!$return){
                return false;
            }
        }
        catch (Exception $e) {
            return false;
        }
        return true;

    }

    /**
     * @param string $cronMinutes
     * @return bool
     */
    protected function isMinutesPresentable($cronMinutes)
    {
        /**
         *  not presentable if cronMinutes do not match any of the following values:
         *  * (every minute),
         *   *slash{one or more digits} (e.g., *slash15 - every 15 minutes)
         *  string containing comma (e.g. 10,20,30 - specified minutes),
         *  {one or more digits}-{one or more digits} (e.g. 40-50 - from 40 to 50 min),
         *  {one or more digits} (e.g. 0 - at the beginnin of every hour)
         */
        if ($cronMinutes !== '*' && !preg_match('#\*/(\d+)#', $cronMinutes) && strpos($cronMinutes, ',') === false
                && !preg_match('#(\d+)-(\d+)#', $cronMinutes) && !preg_match('#\d+#', $cronMinutes)) {
            return false;
        }
        //if minutes contain '/' and '-' signs, e.g. 3-55/15, it is not presentable
        if (strpos($cronMinutes, '/') !== false && strpos($cronMinutes, '-') !== false) {
            return false;
        }
        $everyxMinutesArray = Noovias_Cron_Data_CronExpression_Settings_Minute::getEachMinuteArray();
        /**
         * if cron minutes are of the form *slash{one or more digits} check whether the value of
         * every x minutes is in the Array, which is also used by template rendering cron expression drop-downs
         */
        if (strpos($cronMinutes, '*/') !== false) {
            if (!in_array(intval(substr($cronMinutes, 2)), $everyxMinutesArray)) {
                return false;
            }
        }

        /**
         *  in case of from-to specification:
         *
         * '-' should separate exactly 2 elements
         *  entries should be numeric and between 0 and 59
         */
        if(strpos($cronMinutes, '-') !== false){
            $minutes = explode('-', $cronMinutes);
            if(count($minutes) !== 2){
                return false;
            }
            foreach($minutes as $minute){
                if(!is_numeric($minute)){
                    return false;
                }
                if(intval($minute) > 59 || intval($minute) < 0){
                    return false;
                }
            }
        }

        if(strpos($cronMinutes, ',') !== false){
            $minutes = explode(',', $cronMinutes);
            foreach($minutes as $minute){
                if(!is_numeric($minute)){
                    return false;
                }
                if(intval($minute) > 59 || intval($minute) < 0){
                    return false;
                }
            }
        }

        if(preg_match('#\d+#', $cronMinutes)){
            if(intval($cronMinutes) > 59 || intval($cronMinutes) < 0){
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $cronHours
     * @return bool
     */
    protected function isHoursPresentable($cronHours)
    {
        /**
         *  not presentable if cronHours do not match any of the following values:
         *  * (every hour),
         *   *slash{one or more digits} (e.g., *slash8 - every 8 hours)
         *  string containing comma (e.g. 0,8,16 - specified hours),
         *  {one or more digits}-{one or more digits} (e.g. 16-19 - from 16 to 19 h),
         *  {one or more digits} (e.g. 0 - midnight)
         */
        if ($cronHours !== '*' && !preg_match('#\*/(\d+)#', $cronHours) && strpos($cronHours, ',') === false
                && !preg_match('#(\d+)-(\d+)#', $cronHours) && !preg_match('#\d+#', $cronHours)
        ) {
            return false;
        }
        //if hours contain '/' and '-' signs, e.g. 3-11/2, it is not presentable
        if (strpos($cronHours, '/') !== false && strpos($cronHours, '-') !== false) {
            return false;
        }
        $everyxHoursArray = Noovias_Cron_Data_CronExpression_Settings_Hour::getEachHourArray();
        /**
         * if cron hours are of the form *slash{one or more digits} check whether the value of
         * every x hours is in the Array, which is also used by template rendering cron expression drop-downs
         */
        if (strpos($cronHours, '*/') !== false) {
            if (!in_array(intval(substr($cronHours, 2)), $everyxHoursArray)) {
                return false;
            }
        }

        /**
         *  in case of from-to specification:
         *
         * '-' should separate exactly 2 elements
         *  entries should be numeric and between 0 and 23
         */
        if(strpos($cronHours, '-') !== false){
            $hours = explode('-', $cronHours);
            if(count($hours) !== 2){
                return false;
            }
            foreach($hours as $hour){
                if(!is_numeric($hour)){
                    return false;
                }
                if(intval($hour) > 23 || intval($hour) < 0){
                    return false;
                }
            }
        }

        if(strpos($cronHours, ',') !== false){
            $hours = explode(',', $cronHours);
            foreach($hours as $hour){
                if(!is_numeric($hour)){
                    return false;
                }
                if(intval($hour) > 23 || intval($hour) < 0){
                    return false;
                }
            }
        }

        if(preg_match('#\d+#', $cronHours)){
            if(intval($cronHours) > 23 || intval($cronHours) < 0){
                return false;
            }
        }

        return true;

    }

    /**
     * @param string $cronDayOfMonth
     * @return bool
     */
    protected function isDayOfMonthPresentable($cronDayOfMonth)
    {
        /**
         * not presentable if cronDayOfMonth does not match any of the following values:
         * * (every day of month),
         * string containing comma (e.g. 10,15,20 - specified day of month)
         * {one or more digits}-{one or more digits} (e.g. 5-15 - from 5th to 15th of month)
         * {one or more digits} (e.g. 15 - only one day of month specified)
         */
        if ($cronDayOfMonth !== '*' && strpos($cronDayOfMonth, ',') === false && !preg_match('#(\d+)-(\d+)#', $cronDayOfMonth)
                && !preg_match('#\d+#', $cronDayOfMonth)
        ) {
            return false;
        }
        /**
         * if cron day of Month contains slash, expression is not presentable, as the expression like
         * every x day of month is not supported
         */
        if (strpos($cronDayOfMonth, '/') !== false) {
            return false;
        }

        /**
         * in case of from-to specification:
         *
         * '-' should should separate exactly 2 elements
         * entries should be numeric ant between 1 and 31
         */
        if(strpos($cronDayOfMonth, '-') !== false){
            $days = explode('-', $cronDayOfMonth);
            if(count($days) !== 2){
                return false;
            }
            foreach($days as $day){
                if(!is_numeric(($day))){
                    return false;
                }
                if(intval($day) > 31 || intval($day) < 1){
                    return false;
                }
            }
        }

        if(strpos($cronDayOfMonth, ',') !== false){
            $days = explode(',', $cronDayOfMonth);
            foreach($days as $day)
            {
              if(!is_numeric($day)){
                  return false;
              }
              if(intval($day) > 31 || intval($day) < 1){
                  return false;
              }
            }
        }

        if(preg_match('#\d+#', $cronDayOfMonth)){
            if(intval($cronDayOfMonth) > 31 || intval($cronDayOfMonth) < 1){
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $cronMonth
     * @return bool
     */
    protected function isMonthPresentable($cronMonth)
    {
        /**
         * not presentable if cronMonth does not match any of the following values:
         * * (every month),
         * string containing comma (e.g. JAN,MAR,MAY - specified month)
         * {one or more alphanumeric signs}-{one or more a.n. signs} (e.g. MAR-MAY - from March to May)
         * {one or more alphanumeric signs} (e.g. AUG - only one month specified)
         */
        if ($cronMonth !== '*' && strpos($cronMonth, ',') === false && !preg_match('#(\w+)-(\w+)#', $cronMonth)
                && !preg_match('#\w+#', $cronMonth)
        ) {
            return false;
        }
        /**
         * expression like e.g. every 3 month not supported
         */
        if (strpos($cronMonth, '/') !== false) {
            return false;
        }

        $monthArray = Noovias_Cron_Data_CronExpression_Settings_Month::getCronMonthArray();
        /**
         * in case of from-to specification
         * the month values should be contained in the Array, which is also used by the template
         * rendering cron expression drop-downs
         */
        if (strpos($cronMonth, '-')) {
            preg_match('#(\w+)-(\w+)#', $cronMonth, $pockets);
            if (!in_array($pockets[1], $monthArray) || !in_array($pockets[2], $monthArray)) {
                return false;
            }
        }
        /**
         * if months are specified (string containing comma),
         * every month value should be contained in the month array
         */
        if (strpos($cronMonth, ',') !== false) {
            $cronMonthArray = explode(',', $cronMonth);
            foreach ($cronMonthArray as $item) {
                if (!in_array($item, $monthArray)) {
                    return false;
                }
            }
        }
        /**
         * in case when only one month is specified, it should be contained in
         * the month array
         */
        if (preg_match('#\w+#', $cronMonth, $pockets)) {
            if (!in_array($pockets[0], $monthArray)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $cronDayOfWeek
     * @return bool
     */
    protected function isDayOfWeekPresentable($cronDayOfWeek)
    {
        /**
         * not presentable if cronDayOfWeek does not match any of the following values:
         * * (every day of week),
         * string containing comma (e.g. MON,WED,FRI - specified days of week)
         * {one or more alphanumeric sings}-{one or more a.n. signs} (e.g. FRI-SUN - from Friday to Sunday)
         * {one or more alphanumeric signs} (e.g. MON - only one day of week specified)
         */
        if ($cronDayOfWeek !== '*' && strpos($cronDayOfWeek, ',') === false && !preg_match('#(\w+)-(\w+)#', $cronDayOfWeek)
                && !preg_match('#\w+#', $cronDayOfWeek)
        ) {
            return false;
        }
        /**
         * every x day of week not supported
         */
        if (strpos($cronDayOfWeek, '/') !== false) {
            return false;
        }
        $dayArray = Noovias_Cron_Data_CronExpression_Settings_Day::getCronDayArray();
        /**
         * in case that day of week is specified (many or just one) or the
         * from-to specification is set,
         * the day values should be contained in the day array, which is also used
         * by the template rendering cron expression drop-downs
         */
        if (strpos($cronDayOfWeek, '-')) {
            preg_match('#(\w+)-(\w+)#', $cronDayOfWeek, $pockets);
            if (!in_array($pockets[1], $dayArray) || !in_array($pockets[2], $dayArray)) {
                return false;
            }
        }
        if (strpos($cronDayOfWeek, ',') !== false) {
            $cronDayArray = explode(',', $cronDayOfWeek);
            foreach ($cronDayArray as $item) {
                if (!in_array($item, $dayArray)) {
                    return false;
                }
            }
        }
        if (preg_match('#\w+#', $cronDayOfWeek, $pockets)) {
            if (!in_array($pockets[0], $dayArray)) {
                return false;
            }
        }
        return true;
    }
}