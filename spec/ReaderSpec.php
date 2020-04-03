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

    it('maps the result of a context dependent computation', function () {
        $reader = Reader::reader(fn($state) => (string) $state);

        $mappedReader = $reader->map(fn($value) => $value *2);

        $result = $mappedReader->runReader(42);

        expect($result)->toEqual('84');
    });
});
