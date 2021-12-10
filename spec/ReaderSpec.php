<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Reader;

describe('Reader', function () {
    it('map', function () {
        $mapper = static fn (int $x): int => $x * 5;

        $result = Reader::reader(fn (int $x): int => $x + 2)->map($mapper)->runReader(10);

        expect($result)->toEqual(60);
    });

    it('apply', function () {
        $add = static fn ($x) => static fn (int $y): int => $x + $y;

        $result = Reader::reader(fn (int $x): int => $x + 2)->apply(Reader::reader($add))->runReader(10);

        expect($result)->toEqual(22);
    });

    it('bind', function () {
        $bind = static fn (int $x) => Reader::reader(fn (int $y) => $x + $y);

        $result = Reader::reader(fn (int $x): int => $x + 2)->bind($bind)->runReader(10);

        expect($result)->toEqual(22);
    });
});
