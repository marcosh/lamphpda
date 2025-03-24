<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Maybe;
use Marcosh\LamPHPda\Unit;

/**
 * @param Maybe<Int> $m
 * @return Unit
 */
function foo (Maybe $m) {
    return $m->eval(
        new Unit(),
        fn(int $i) => new Unit()
    );
}

describe('MaybeUnit', function () {
    it('works with Nothing', function () {
        expect(foo(Maybe::nothing()))->toEqual(new Unit());
    });

    it('works with Just', function () {
        expect(foo(Maybe::just(42)))->toEqual(new Unit());
    });
});
