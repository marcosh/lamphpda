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

    it('mapsLeft correctly a left', function () {
        $either = Either::left(42);

        $result = $either->mapLeft(
            (fn($value) => $value * 2)
        );

        expect($result)->toEqual(Either::left(84));
    });

    it('mapsLeft correctly a right', function () {
        $either = Either::right(42);

        $result = $either->mapLeft(
            (fn($value) => $value * 2)
        );

        expect($result)->toEqual(Either::right(42));
    });

    it('applies correctly a left to a left', function () {
        $f = Either::left(42);
        $either = Either::left('a');

        expect($either->apply($f))->toEqual($f);
    });

    it('applies correctly a left to a right', function () {
        $f = Either::left(42);
        $either = Either::right('a');

        expect($either->apply($f))->toEqual($f);
    });

    it('applies correctly a right to a left', function () {
        $f = Either::right(fn($x) => $x * 2);
        $either = Either::left('a');

        expect($either->apply($f))->toEqual($either);
    });

    it('applies correctly a right to a right', function () {
        $f = Either::right(fn($x) => $x * 2);
        $either = Either::right(42);

        expect($either->apply($f))->toEqual(Either::right(84));
    });

    it('creates a right as pure', function () {
        expect(Either::pure(42))->toEqual(Either::right(42));
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

    it('binds a callable returning a right to a left to get a left', function () {
        $either = Either::left(42);
        $f = fn($x) => Either::right($x);

        expect($either->bind($f))->toEqual(Either::left(42));
    });

    it('binds a callable returning a left to a left to get a left', function () {
        $either = Either::left(42);
        $f = fn($x) => Either::left('a');

        expect($either->bind($f))->toEqual(Either::left(42));
    });

    it('binds a callable returning a left to a right to return a left', function () {
        $either = Either::right(42);
        $f = fn($x) => Either::left('a');

        expect($either->bind($f))->toEqual(Either::left('a'));
    });

    it('binds a callable returning a right to a right to return a right', function () {
        $either = Either::right(42);
        $f = fn($x) => Either::right($x * 2);

        expect($either->bind($f))->toEqual(Either::right(84));
    });

    it('folds a left using the unit value', function () {
        $either = Either::left(42);
        $f = fn($x, $y) => $x + $y;

        expect($either->foldr($f, 0))->toBe(0);
    });

    it('folds a right using the inner value and the unit', function () {
        $either = Either::right(42);
        $f = fn($x, $y) => $x + $y;

        expect($either->foldr($f, 37))->toBe(37 + 42);
    });
});
