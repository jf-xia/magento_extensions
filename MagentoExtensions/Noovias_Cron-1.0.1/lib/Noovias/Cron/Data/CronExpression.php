<?php
/**
 * Noovias_Cron_Data_CronExpression
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

class Noovias_Cron_Data_CronExpression
{
    const OPTION_EVERY = 'every';
    const OPTION_EVERYX = 'everyx';
    const OPTION_SPECIFY = 'specify';
    const OPTION_FROMTO = 'fromto';

    protected $minuteOption = null;
    protected $everyXMinutes = null;
    protected $specifyMinutes = null;
    protected $fromMinute = null;
    protected $toMinute = null;

    protected $hourOption = null;
    protected $everyXHours = null;
    protected $specifyHours = null;
    protected $fromHour = null;
    protected $toHour = null;

    protected $dayOfMonthOption = null;
    protected $specifyDayOfMonth = null;
    protected $fromDayOfMonth = null;
    protected $toDayOfMonth = null;

    protected $monthOption = null;
    protected $specifyMonth = null;
    protected $fromMonth = null;
    protected $toMonth = null;

    protected $dayOfWeekOption = null;
    protected $specifyDayOfWeek = null;
    protected $fromDayOfWeek = null;
    protected $toDayOfWeek = null;

    const EXCEPTION_PROPERTY_NOT_FOUND = 'Object property couldn\'t be found: ';

    public function __construct($params=array())
    {
        foreach($params as $varname => $value){
            if(property_exists(get_class($this), $varname)){
                 $this->set($varname, $value);
            }
        }
    }

    /**
     * Generic setter
     *
     * @param string $fieldName
     * @param $value
     */
    public function set($fieldName, $value){
        if(!property_exists(get_class($this), $fieldName)){
            throw new Noovias_Cron_Exception_PropertyNotFound(self::EXCEPTION_PROPERTY_NOT_FOUND . $fieldName);
        }
        $setter = 'set' . ucfirst($fieldName);
        $this->$setter($value);
    }

    /**
     * Generic getter
     *
     * @param string $fieldName
     * @return mixed
     * @throws Exception
     */
    public function get($fieldName)
    {
        if(!property_exists(get_class($this), $fieldName)){
            throw new Noovias_Cron_Exception_PropertyNotFound(self::EXCEPTION_PROPERTY_NOT_FOUND . $fieldName);
        }
        $getter = 'get'. ucfirst($fieldName);
        return $this->$getter();
    }


    /**
     * @return string
     */
    public function getDayOfMonthOption()
    {
        return $this->dayOfMonthOption;
    }

    /**
     * @return string
     */
    public function getDayOfWeekOption()
    {
        return $this->dayOfWeekOption;
    }

    /**
     * @return string
     */
    public function getEveryXHours()
    {
        return $this->everyXHours;
    }

    /**
     * @return string
     */
    public function getEveryXMinutes()
    {
        return $this->everyXMinutes;
    }

    /**
     * @return string
     */
    public function getFromDayOfMonth()
    {
        return $this->fromDayOfMonth;
    }

    /**
     * @return string
     */
    public function getFromDayOfWeek()
    {
        return $this->fromDayOfWeek;
    }

    /**
     * @return string
     */
    public function getFromHour()
    {
        return $this->fromHour;
    }

    /**
     * @return string
     */
    public function getFromMinute()
    {
        return $this->fromMinute;
    }

    /**
     * @return string
     */
    public function getFromMonth()
    {
        return $this->fromMonth;
    }

    /**
     * @return string
     */
    public function getHourOption()
    {
        return $this->hourOption;
    }

    /**
     * @return string
     */
    public function getMinuteOption()
    {
        return $this->minuteOption;
    }

    /**
     * @return string
     */
    public function getMonthOption()
    {
        return $this->monthOption;
    }

    /**
     * @return array
     */
    public function getSpecifyDayOfMonth()
    {
        return $this->specifyDayOfMonth;
    }

    /**
     * @return array
     */
    public function getSpecifyDayOfWeek()
    {
        return $this->specifyDayOfWeek;
    }

    /**
     * @return array
     */
    public function getSpecifyHours()
    {
        return $this->specifyHours;
    }

    /**
     * @return array
     */
    public function getSpecifyMinutes()
    {
        return $this->specifyMinutes;
    }

    /**
     * @return array
     */
    public function getSpecifyMonth()
    {
        return $this->specifyMonth;
    }

    /**
     * @return string
     */
    public function getToDayOfMonth()
    {
        return $this->toDayOfMonth;
    }

    /**
     * @return string
     */
    public function getToDayOfWeek()
    {
        return $this->toDayOfWeek;
    }

    /**
     * @return string
     */
    public function getToHour()
    {
        return $this->toHour;
    }

    /**
     * @return string
     */
    public function getToMinute()
    {
        return $this->toMinute;
    }

    /**
     * @return string
     */
    public function getToMonth()
    {
        return $this->toMonth;
    }

    /**
     * @param string $dayofmonthoption
     */
    public function setDayOfMonthOption($dayofmonthoption)
    {
        $this->dayOfMonthOption = $dayofmonthoption;
    }

    /**
     * @param string $dayofweekoption
     */
    public function setDayOfWeekOption($dayofweekoption)
    {
        $this->dayOfWeekOption = $dayofweekoption;
    }

    /**
     * @param string $everyxhours
     */
    public function setEveryXHours($everyxhours)
    {
        $this->everyXHours = $everyxhours;
    }

    /**
     * @param string $everyxminutes
     */
    public function setEveryXMinutes($everyxminutes)
    {
        $this->everyXMinutes = $everyxminutes;
    }

    /**
     * @param string $fromdayofmonth
     */
    public function setFromDayOfMonth($fromdayofmonth)
    {
        $this->fromDayOfMonth = $fromdayofmonth;
    }

    /**
     * @param string $fromdayofweek
     */
    public function setFromDayOfWeek($fromdayofweek)
    {
        $this->fromDayOfWeek = $fromdayofweek;
    }

    /**
     * @param string $fromhour
     */
    public function setFromHour($fromhour)
    {
        $this->fromHour = $fromhour;
    }

    /**
     * @param string $fromminute
     */
    public function setFromMinute($fromminute)
    {
        $this->fromMinute = $fromminute;
    }

    /**
     * @param string $frommonth
     */
    public function setFromMonth($frommonth)
    {
        $this->fromMonth = $frommonth;
    }

    /**
     * @param string $houroption
     */
    public function setHourOption($houroption)
    {
        $this->hourOption = $houroption;
    }

    /**
     * @param string $minuteoption
     */
    public function setMinuteOption($minuteoption)
    {
        $this->minuteOption = $minuteoption;
    }

    /**
     * @param string $monthoption
     */
    public function setMonthOption($monthoption)
    {
        $this->monthOption = $monthoption;
    }

    /**
     * @param array $specifydayofmonth
     */
    public function setSpecifyDayOfMonth($specifydayofmonth)
    {
        $this->specifyDayOfMonth = $specifydayofmonth;
    }

    /**
     * @param array $specifydayofweek
     */
    public function setSpecifyDayOfWeek($specifydayofweek)
    {
        $this->specifyDayOfWeek = $specifydayofweek;
    }

    /**
     * @param array $specifyhours
     */
    public function setSpecifyHours($specifyhours)
    {
        $this->specifyHours = $specifyhours;
    }

    /**
     * @param array $specifyminutes
     */
    public function setSpecifyMinutes($specifyminutes)
    {
        $this->specifyMinutes = $specifyminutes;
    }

    /**
     * @param array $specifymonth
     */
    public function setSpecifyMonth($specifymonth)
    {
        $this->specifyMonth = $specifymonth;
    }

    /**
     * @param string $todayofmonth
     */
    public function setToDayOfMonth($todayofmonth)
    {
        $this->toDayOfMonth = $todayofmonth;
    }

    /**
     * @param string $todayofweek
     */
    public function setToDayOfWeek($todayofweek)
    {
        $this->toDayOfWeek = $todayofweek;
    }

    /**
     * @param string $tohour
     */
    public function setToHour($tohour)
    {
        $this->toHour = $tohour;
    }

    /**
     * @param string $tominute
     */
    public function setToMinute($tominute)
    {
        $this->toMinute = $tominute;
    }

    /**
     * @param string $tomonth
     */
    public function setToMonth($tomonth)
    {
        $this->toMonth = $tomonth;
    }
}