<?php
class Noovias_Cron_Data_CronExpression_Settings_Month
{
    const CRON_MONTH_JANUARY = 'JAN';
    const CRON_MONTH_FEBRUARY = 'FEB';
    const CRON_MONTH_MARCH = 'MAR';
    const CRON_MONTH_APRIL = 'APR';
    const CRON_MONTH_MAY = 'MAY';
    const CRON_MONTH_JUNE = 'JUN';
    const CRON_MONTH_JULY = 'JUL';
    const CRON_MONTH_AUGUST = 'AUG';
    const CRON_MONTH_SEPTEMBER = 'SEP';
    const CRON_MONTH_OCTOBER = 'OCT';
    const CRON_MONTH_NOVEMBER = 'NOV';
    const CRON_MONTH_DECEMBER = 'DEC';

    /**
     * @return array
     */
    public static function getCronMonthArray()
    {
        return array(
            self::CRON_MONTH_JANUARY,
            self::CRON_MONTH_FEBRUARY,
            self::CRON_MONTH_MARCH,
            self::CRON_MONTH_APRIL,
            self::CRON_MONTH_MAY,
            self::CRON_MONTH_JUNE,
            self::CRON_MONTH_JULY,
            self::CRON_MONTH_AUGUST,
            self::CRON_MONTH_SEPTEMBER,
            self::CRON_MONTH_OCTOBER,
            self::CRON_MONTH_NOVEMBER,
            self::CRON_MONTH_DECEMBER
        );
    }
}