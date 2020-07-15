<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Spec;

use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\State;

describe('State', function () {
    it('runState performs a state update', function () {
        $state = State::state(static fn ($state) => Pair::pair($state / 2, $state * 2));

        $result = $state->runState(42);

        expect($result)->toEqual(Pair::pair(21, 84));
    });

    it('maps the result of a stateful computation', function () {
        $state = State::state(static fn ($state) => Pair::pair($state / 2, $state * 2));

        $mappedState = $state->map(static fn ($value) => $value / 3);

        $result = $mappedState->runState(42);

        expect($result)->toEqual(Pair::pair(7, 84));
    });

    it('applies a wrapped callable to a wrapped value', function () {
        $state = State::state(static fn ($state) => Pair::pair($state / 2, $state * 2));
        $applyState = State::state(static fn ($state) => Pair::pair(static fn ($int) => $state * $int, $state * 5));

        $appliedState = $state->apply($applyState);

        $result = $appliedState->runState(42);

        expect($result)->toEqual(Pair::pair(42 * (42 * 5 / 2), 42 * 2 * 5));
    });

    it('creates a constant state-preserving function as pure', function () {
        $state = State::pure(42);

        expect($state->runState(13))->toEqual(Pair::pair(42, 13));
    });

    it('binds a callable correctly', function () {
        $state = State::state(static fn ($state) => Pair::pair($state / 2, $state * 2));

        $f = static fn ($x) => State::state(static fn ($state) => Pair::pair($state * $x, $state + $x));

        expect($state->bind($f)->runState(42))->toEqual(Pair::pair(42 * 2 * 42 / 2, 42 * 2 + 42 / 2));
    });
});
