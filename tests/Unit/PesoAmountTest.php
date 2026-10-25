<?php

use App\Support\PesoAmount;

it('spells receipt amounts without requiring intl', function ($amount, string $words) {
    expect(PesoAmount::inWords($amount))->toBe($words);
})->with([
    [0, 'ZERO PESOS'], [1, 'ONE PESOS'],
    ['1200.50', 'ONE THOUSAND TWO HUNDRED PESOS AND FIFTY CENTAVOS'],
    [99, 'NINETY NINE PESOS'], [1000001, 'ONE MILLION ONE PESOS'],
]);

it('rejects invalid receipt amounts', function () {
    PesoAmount::inWords(-1);
})->throws(InvalidArgumentException::class);
