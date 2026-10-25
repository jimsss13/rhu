<?php

use App\Support\PhilippineMobileNumber;

it('normalizes supported Philippine mobile formats', function (string $input) {
    expect(PhilippineMobileNumber::normalize($input))->toBe('639171234567');
})->with(['09171234567', '9171234567', '639171234567', '+63 917 123 4567']);

it('rejects malformed mobile numbers', function () {
    PhilippineMobileNumber::normalize('12345');
})->throws(InvalidArgumentException::class);
