<?php

namespace App\Support;

use InvalidArgumentException;

class PhilippineMobileNumber
{
    public static function normalize(string $number): string
    {
        $digits = preg_replace('/[^0-9]/', '', $number);

        if (preg_match('/^09\d{9}$/', $digits)) {
            return '63'.substr($digits, 1);
        }

        if (preg_match('/^9\d{9}$/', $digits)) {
            return '63'.$digits;
        }

        if (preg_match('/^639\d{9}$/', $digits)) {
            return $digits;
        }

        throw new InvalidArgumentException('Invalid Philippine mobile number.');
    }
}
