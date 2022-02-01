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

    it('applies a stateful function to a stateful value', function () {
        $stateF = State::state(fn ($s) => Pair::pair($s + 1, fn ($a) => $s + $a));
        $stateA = State::state(fn ($s) => Pair::pair($s * 2, $s));

        expect($stateA->apply($stateF)->runState(42))->toEqual(Pair::pair((42 + 1) * 2, 42 + 43));
    });

    it('creates a pure value leaving the state unchanged', function () {
        $state = State::pure(42);

        expect($state->runState(37))->toEqual(Pair::pair(37, 42));
    });

    it('binds a function to a stateful value', function () {
        $stateA = State::state(fn ($s) => Pair::pair($s * 2, $s + 1));
        $stateF = fn ($a) => State::state(fn ($s) => Pair::pair($s * $a, $s - $a));

        expect($stateA->bind($stateF)->runState(42))->toEqual(Pair::pair(42 * 2 * (42 + 1), (42 * 2) - (42 + 1)));
    });
});
