<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Reader;

describe('Reader', function () {
    it('apply', function () {
        $addTwo = static fn (int $x): int => $x + 2;

        $result = Reader::of($addTwo)->apply(Reader::of(10))->runReader(50);

        expect($result)->toEqual(12);
    });

    it('bind', function () {
        $bind = static fn (int $x) => Reader::of(5)->map(static fn (int $y): int => $x * $y);

        $result = Reader::of(2)->bind($bind)->runReader('foobar');

        expect($result)->toEqual(10);
    });

    it('map', function () {
        $mapper = static fn (int $x): int => $x * 5;

        $result = Reader::of(2)->map($mapper)->runReader(20);

        expect($result)->toEqual(10);
    });
});
