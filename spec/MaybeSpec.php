<?php

declare(strict_types=1);

namespace Marcosh\LamPHPdaSpec;

use Marcosh\LamPHPda\Maybe;

describe('Maybe', function () {
    it('uses nothing case when nothing', function () {
        $maybe = Maybe::nothing();

        $result = $maybe->eval(
            42,
            fn($value) => $value
        );

        expect($result)->toEqual(42);
    });

    it('uses just case when just', function () {
        $maybe = Maybe::just(42);

        $result = $maybe->eval(
            0,
            (fn($value) => $value * 2)
        );

        expect($result)->toEqual(84);
    });

    it('maps a nothing to a nothing', function () {
        $maybe = Maybe::nothing();

        $mapped = $maybe->map(
            fn($value) => $value * 2
        );

        expect($mapped)->toEqual(Maybe::nothing());
    });

    it('maps a just to a mapped just', function () {
        $maybe = Maybe::just(42);

        $mapped = $maybe->map(
            fn($value) => $value * 2
        );

        expect($mapped)->toEqual(Maybe::just(84));
    });

    it('recognises a nothing', function () {
       $maybe = Maybe::nothing();

       expect($maybe->isNothing())->toBeTruthy();
       expect($maybe->isJust())->toBeFalsy();
    });

    it('recognises a just', function () {
        $maybe = Maybe::just(42);

        expect($maybe->isNothing())->toBeFalsy();
        expect($maybe->isJust())->toBeTruthy();
    });

    it('applies Nothing to Nothing to get Nothing', function () {
        $maybe = Maybe::nothing();
        $maybeF = Maybe::nothing();

        expect($maybe->apply($maybeF))->toEqual(Maybe::nothing());
    });

    it('applies something to Nothing to get Nothing', function () {
        $maybe = Maybe::nothing();
        $maybeF = Maybe::just(fn($x) => $x);

        expect($maybe->apply($maybeF))->toEqual(Maybe::nothing());
    });

    it('applies Nothing to something to get Nothing', function () {
        $maybe = Maybe::just(42);
        $maybeF = Maybe::nothing();

        expect($maybe->apply($maybeF))->toEqual(Maybe::nothing());
    });

    it('applies something to something to get something', function () {
        $maybe = Maybe::just(42);
        $maybeF = Maybe::just(fn($x) => $x * 2);

        expect($maybe->apply($maybeF))->toEqual(Maybe::just(84));
    });

    it('creates a just as pure', function () {
        expect(Maybe::pure(42))->toEqual(Maybe::just(42));
    });
});
