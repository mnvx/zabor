<?php

namespace App\Helpers;

class Date
{
    public static function getDefaultTimeZone()
    {
        return 'Europe/Moscow';
    }

    public static function getDefaultDatetimeFormat()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * @param string $date
     * @return string
     */
    public static function translateToDefaultTimezone($date)
    {
        $dateAsDate = (new \DateTime($date))->setTimezone(new \DateTimeZone(self::getDefaultTimeZone()));
        return $dateAsDate->format(self::getDefaultDatetimeFormat());
    }
}