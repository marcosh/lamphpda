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
});
