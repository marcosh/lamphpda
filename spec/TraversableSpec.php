<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Traversable;

describe('Traversable', function () {
    it('folds an empty list using the unit value', function () {
        $emptyList = Traversable::fromArray([]);
        $f = fn($x, $y) => $x + $y;

        expect($emptyList->foldr($f, 0))->toBe(0);
    });

    it('folds a list using the elements and the unit', function () {
        $maybe = Traversable::fromArray([1, 2, 3]);
        $f = fn($x, $y) => $x + $y;

        expect($maybe->foldr($f, 0))->toBe(6);
    });
});
