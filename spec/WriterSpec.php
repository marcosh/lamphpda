<?php

declare(strict_types=1);

namespace Marcosh\LamPHPdaSpec;

use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\Writer;

describe('Writer', function () {
    it('exec returns the inner pair', function () {
        $writer = Writer::writer(Pair::pair(42, "a"));

        $result = $writer->exec();

        expect($result)->toEqual(Pair::pair(42, "a"));
    });
});
