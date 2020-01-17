<?php

declare(strict_types=1);

namespace Marcosh\LamPHPdaSpec;

use Marcosh\LamPHPda\Either;

describe('Either', function () {
    it('uses left case when left', function () {
        $either = Either::left(42);

        $result = $either->eval(
            (fn($value) => $value * 2),
            (fn($value) => $value / 2)
        );

        expect($result)->toEqual(84);
    });

    it('uses just case when just', function () {
        $either = Either::right(42);

        $result = $either->eval(
            (fn($value) => $value * 2),
            (fn($value) => $value / 2)
        );

        expect($result)->toEqual(21);
    });
});
