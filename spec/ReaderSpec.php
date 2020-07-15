<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Spec;

use Marcosh\LamPHPda\Reader;

describe('Reader', function () {
    it('runReader acts on current state', function () {
        $reader = Reader::reader(static fn ($state) => (string) $state);

        $result = $reader->runReader(42);

        expect($result)->toEqual('42');
    });

    it('maps the result of a context dependent computation', function () {
        $reader = Reader::reader(static fn ($state) => (string) $state);

        $mappedReader = $reader->map(static fn ($value) => $value * 2);

        $result = $mappedReader->runReader(42);

        expect($result)->toEqual('84');
    });

    it('applies a wrapped function to a wrapped value', function () {
        $reader = Reader::reader(static fn ($state) => $state * 2);
        $applyReader = Reader::reader(static fn ($state) => static fn ($value) => $state * $value);

        $appliedReader = $reader->apply($applyReader);

        $result = $appliedReader->runReader(42);

        expect($result)->toEqual(42 * 42 * 2);
    });

    it('creates a constant function as pure', function () {
        $reader = Reader::pure(42);

        expect($reader->runReader(13))->toBe(42);
    });

    it('binds a callable correctly', function () {
        $reader = Reader::reader(static fn ($state) => $state * 2);

        $f = static fn ($x) => Reader::reader(static fn ($state) => $x * $state);

        expect($reader->bind($f)->runReader(42))->toBe(42 * 42 * 2);
    });
});
