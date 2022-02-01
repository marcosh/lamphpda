<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\State;

describe('State', function () {
    it('runs a stateful computation', function () {
        $state = State::state(fn ($s) => Pair::pair($s, $s));

        expect($state->runState(42))->toEqual(Pair::pair(42, 42));
    });

    it('maps correctly a stateful value', function () {
        $state = State::state(fn ($s) => Pair::pair($s, $s));

        expect($state->map(fn ($x) => $x + 5)->runState(42))->toEqual(Pair::pair(42, 47));
    });
});
