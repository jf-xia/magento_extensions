<?php
class Noovias_Cron_Data_CronExpression_Settings_Day
{
    const CRON_DAY_MONDAY = 'MON';
    const CRON_DAY_TUESDAY = 'TUE';
    const CRON_DAY_WEDNESDAY = 'WED';
    const CRON_DAY_THURSDAY = 'THU';
    const CRON_DAY_FRIDAY = 'FRI';
    const CRON_DAY_SATURDAY = 'SAT';
    const CRON_DAY_SUNDAY = 'SUN';

    /**
     * @return array
     */
    public static function getCronDayArray()
    {
        return array(
            self::CRON_DAY_SUNDAY,
            self::CRON_DAY_MONDAY,
            self::CRON_DAY_TUESDAY,
            self::CRON_DAY_WEDNESDAY,
            self::CRON_DAY_THURSDAY,
            self::CRON_DAY_FRIDAY,
            self::CRON_DAY_SATURDAY,
        );
    }
}