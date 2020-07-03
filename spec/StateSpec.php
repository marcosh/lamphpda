<?php

declare(strict_types=1);

namespace Marcosh\LamPHPdaSpec;

use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\State;

describe('State', function () {
    it('runState performs a state update', function () {
        $state = State::state(fn($state) => Pair::pair($state / 2, $state * 2));

        $result = $state->runState(42);

        expect($result)->toEqual(Pair::pair(21, 84));
    });

    it('maps the result of a stateful computation', function () {
        $state = State::state(fn($state) => Pair::pair($state / 2, $state * 2));

        $mappedState = $state->map(fn($value) => $value / 3);

        $result = $mappedState->runState(42);

        expect($result)->toEqual(Pair::pair(7, 84));
    });

    it('applies a wrapped callable to a wrapped value', function () {
        $state = State::state(fn($state) => Pair::pair($state / 2, $state * 2));
        $applyState = State::state(fn($state) => Pair::pair(fn($int) => $state * $int, $state * 5));

        $appliedState = $state->apply($applyState);

        $result = $appliedState->runState(42);

        expect($result)->toEqual(Pair::pair(42 * (42 * 5 / 2), 42 * 2 * 5));
    });

    it('creates a constant state-preserving function as pure', function () {
        $state = State::pure(42);

        expect($state->runState(13))->toEqual(Pair::pair(42, 13));
    });
});
