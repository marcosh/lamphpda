<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Instances\ConstantSemigroup;

describe('ConstantSemigroup', function () {
    it('returns always the same element', function () {
        expect((new ConstantSemigroup(42))->append(37, 53))->toEqual(42);
    });
});