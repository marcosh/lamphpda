<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Spec;

use Marcosh\LamPHPda\Pair;

describe('Pair', function () {
    it('uncurry uses correctly both arguments', function () {
        $maybe = Pair::pair(3, 'hi!');

        $result = $maybe->uncurry(
            (static fn ($left, $right) => str_repeat($right, $left))
        );

        expect($result)->toEqual('hi!hi!hi!');
    });

    it('maps on the second component', function () {
        $maybe = Pair::pair(3, 'hi!');

        $result = $maybe->map(
            static fn ($right) => str_repeat($right, 2)
        );

        expect($result)->toEqual(Pair::pair(3, 'hi!hi!'));
    });

    it('lmaps on the first component', function () {
        $maybe = Pair::pair(3, 'hi!');

        $result = $maybe->lmap(
            static fn ($left) => $left * 2
        );

        expect($result)->toEqual(Pair::pair(6, 'hi!'));
    });
});
