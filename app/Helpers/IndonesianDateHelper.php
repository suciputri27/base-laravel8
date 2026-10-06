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

    public static function day($day): string
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return $days[(int) $day] ?? '-';
    }

    public static function fullDate($value): string
    {
        $date = $value instanceof Carbon
            ? $value
            : Carbon::parse($value);

        return self::day($date->dayOfWeekIso) . ', ' . self::date($date);
    }
}
