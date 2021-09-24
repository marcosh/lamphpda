<?php

declare(strict_types=1);

namespace Marcosh\LamPHPdaSpec;

use Marcosh\LamPHPda\Pair;

describe('Pair', function () {
    it('uncurry uses correctly both arguments', function () {
        $pair = Pair::pair(3, "hi!");

        $result = $pair->uncurry(
            (fn($left, $right) => str_repeat($right, $left))
        );

        expect($result)->toEqual("hi!hi!hi!");
    });

    it('maps on the second component', function () {
        $pair = Pair::pair(3, "hi!");

        $result = $pair->map(
            fn($right) => str_repeat($right, 2)
        );

        expect($result)->toEqual(Pair::pair(3, "hi!hi!"));
    });

    it('lmaps on the first component', function () {
        $pair = Pair::pair(3, "hi!");

        $result = $pair->lmap(
            fn($left) => $left * 2
        );

        expect($result)->toEqual(Pair::pair(6, "hi!"));
    });

    it('folds a pair using the right component and the unit', function () {
        $pair = Pair::pair("hi!", 42);
        $f = fn($x, $y) => $x + $y;

        expect($pair->foldr($f, 37))->toBe(37 + 42);
    });
});
