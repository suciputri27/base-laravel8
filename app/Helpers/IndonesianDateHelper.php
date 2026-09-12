<?php

namespace App\Helpers;

use Carbon\Carbon;

class IndonesianDateHelper
{
    public static function date($value): string
    {
        $date = $value instanceof Carbon ? $value : Carbon::parse($value);

        $months = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        return $date->format('d') . ' ' . $months[(int) $date->format('m') - 1] . ' ' . $date->format('Y');
    }

    public static function dateTime($value): string
    {
        $date = $value instanceof Carbon ? $value : Carbon::parse($value);

        return self::date($date) . ', ' . $date->format('H:i') . ' WIB';
    }
}
