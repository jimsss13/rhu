<?php

namespace App\Support;

use InvalidArgumentException;

class PesoAmount
{
    public static function inWords(int|float|string $amount): string
    {
        if (! is_numeric($amount) || $amount < 0 || $amount >= 1000000000000) {
            throw new InvalidArgumentException('Amount must be a nonnegative peso amount below one trillion.');
        }

        [$pesos, $centavos] = explode('.', number_format((float) $amount, 2, '.', ''));

        return strtoupper(self::spell((int) $pesos).' PESOS'
            .((int) $centavos > 0 ? ' AND '.self::spell((int) $centavos).' CENTAVOS' : ''));
    }

    private static function spell(int $number): string
    {
        $small = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
        $tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

        if ($number < 20) {
            return $small[$number];
        }
        if ($number < 100) {
            return $tens[intdiv($number, 10)].($number % 10 ? ' '.self::spell($number % 10) : '');
        }
        foreach ([1000000000 => 'billion', 1000000 => 'million', 1000 => 'thousand', 100 => 'hundred'] as $scale => $name) {
            if ($number >= $scale) {
                return self::spell(intdiv($number, $scale)).' '.$name
                    .($number % $scale ? ' '.self::spell($number % $scale) : '');
            }
        }

        return '';
    }
}
