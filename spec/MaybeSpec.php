<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Instances\Maybe\MaybeMonad;
use Marcosh\LamPHPda\Maybe;

describe('Maybe', function () {
    it('applies Nothing to Nothing to get Nothing', function () {
        $maybe = Maybe::nothing();
        $maybeF = Maybe::nothing();

        expect($maybe->iapply(new MaybeMonad(), $maybeF))->toEqual(Maybe::nothing());
    });

    it('applies something to Nothing to get Nothing', function () {
        $maybe = Maybe::nothing();
        $maybeF = Maybe::just(fn($x) => $x);

        expect($maybe->iapply(new MaybeMonad(), $maybeF))->toEqual(Maybe::nothing());
    });

    it('applies Nothing to something to get Nothing', function () {
        $maybe = Maybe::just(42);
        $maybeF = Maybe::nothing();

        expect($maybe->iapply(new MaybeMonad(), $maybeF))->toEqual(Maybe::nothing());
    });

    it('applies something to something to get something', function () {
        $maybe = Maybe::just(42);
        $maybeF = Maybe::just(fn($x) => $x * 2);

        expect($maybe->iapply(new MaybeMonad(), $maybeF))->toEqual(Maybe::just(84));
    });
});
