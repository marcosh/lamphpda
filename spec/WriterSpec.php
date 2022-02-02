<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\Writer;

describe('Writer', function () {
    it('runs a computation with an added output', function () {
        $writer = Writer::writer(42, 37);

        expect($writer->runWriter())->toEqual(Pair::pair(42, 37));
    });
});
