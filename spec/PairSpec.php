<?php

declare(strict_types=1);

namespace Marcosh\LamPHPdaSpec;

use Marcosh\LamPHPda\Pair;

describe('Pair', function () {
    it('maps correctly on the second component', function () {
        $pair = Pair::pair(42, 37);

        expect($pair->map(fn(int $x) => $x + 5))->toEqual(Pair::pair(42, 42));
    });
});
