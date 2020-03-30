<?php

declare(strict_types=1);

namespace Marcosh\LamPHPdaSpec;

use Marcosh\LamPHPda\Pair;

describe('Pair', function () {
    it('uncurry uses correctly both arguments', function () {
        $maybe = Pair::pair(3, "hi!");

        $result = $maybe->uncurry(
            (fn($left, $right) => str_repeat($right, $left))
        );

        expect($result)->toEqual("hi!hi!hi!");
    });

    it('maps on the second component', function () {
        $maybe = Pair::pair(3, "hi!");

        $result = $maybe->map(
            fn($right) => str_repeat($right, 2)
        );

        expect($result)->toEqual(Pair::pair(3, "hi!hi!"));
    });

    it('lmaps on the first component', function () {
        $maybe = Pair::pair(3, "hi!");

        $result = $maybe->lmap(
            fn($left) => $left * 2
        );

        expect($result)->toEqual(Pair::pair(6, "hi!"));
    });
});
