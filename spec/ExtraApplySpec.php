<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Instances\Maybe\MaybeApply;
use Marcosh\LamPHPda\Maybe;
use Marcosh\LamPHPda\Typeclass\Extra\ExtraApply;

describe('ExtraApply', function () {
    it('applies correctly a function to 8 arguments', function () {
        $apply = new ExtraApply(new MaybeApply());

        expect($apply->lift8(
            fn ($x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8) => $x1 + $x2 + $x3 + $x4 + $x5 + $x6 + $x7 + $x8,
            Maybe::just(1),
            Maybe::just(2),
            Maybe::just(3),
            Maybe::just(4),
            Maybe::just(5),
            Maybe::just(6),
            Maybe::just(7),
            Maybe::just(8)
        ))->toEqual(Maybe::just(36));
    });
});
