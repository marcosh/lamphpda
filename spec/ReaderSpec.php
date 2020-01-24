<?php

declare(strict_types=1);

namespace Marcosh\LamPHPdaSpec;

use Marcosh\LamPHPda\Reader;

describe('Reader', function () {
    it('runReader acts on current state', function () {
        $reader = Reader::reader(fn($state) => (string) $state);

        $result = $reader->runReader(42);

        expect($result)->toEqual('42');
    });
});
