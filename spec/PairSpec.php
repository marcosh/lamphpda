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
});
