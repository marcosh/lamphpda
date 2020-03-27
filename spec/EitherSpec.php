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

    it('maps correctly a left', function () {
        $either = Either::left(42);

        $result = $either->map(
            (fn($value) => $value * 2)
        );

        expect($result)->toEqual(Either::left(42));
    });

    it('maps correctly a right', function () {
        $either = Either::right(42);

        $result = $either->map(
            (fn($value) => $value * 2)
        );

        expect($result)->toEqual(Either::right(84));
    });

    it('recognises a left', function () {
        $either = Either::left(42);

        expect($either->isLeft())->toBeTruthy();
        expect($either->isRight())->toBeFalsy();
    });

    it('recognises a right', function () {
        $either = Either::right(42);

        expect($either->isLeft())->toBeFalsy();
        expect($either->isRight())->toBeTruthy();
    });
});
