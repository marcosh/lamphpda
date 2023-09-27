<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Instances\ListL\ConcatenationMonoid;
use Marcosh\LamPHPda\Instances\ListL\ListFoldable;
use Marcosh\LamPHPda\ListL;
use Marcosh\LamPHPda\Typeclass\Extra\ExtraFoldable;

describe('ConcatenateListsWithFoldMap', function () {
    it('preserves the order when concatenating lists', function () {
        expect((new ExtraFoldable(new ListFoldable()))->fold(
            new ConcatenationMonoid(),
            new ListL([[1, 2], [3, 4], [5, 6]])
        ))->toBe([1, 2, 3, 4, 5, 6]);
    });
});
