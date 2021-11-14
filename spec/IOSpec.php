<?php

declare(strict_types=1);

use Marcosh\LamPHPda\IO;

describe('IO', function () {
    it('apply', function () {
        $add = static fn (int $x): Closure => static fn (int $y): int => $x + $y;

        $a = IO::pure(2);
        $b = IO::pure(4);

        $result = IO::pure($add)->apply($a)->apply($b)->eval();

        expect($result)->toEqual(6);
    });

    it('bind', function () {
        $getLine = IO::pure('Hello, world!');

        $putStrLn = function (string $str): IO {
            return IO::pure($str);
        };

        $result = $getLine->bind($putStrLn)->eval();

        expect($result)->toEqual('Hello, world!');
    });

    it('map', function () {
        $mapper = static fn (int $x): int => $x + 2;

        $result = IO::pure(2)->map($mapper)->eval();

        expect($result)->toEqual(4);
    });
});
