<?php

declare(strict_types=1);

use Marcosh\LamPHPda\IO;

describe('IO', function () {
    it('evaluates correctly the callable', function () {
        $io = IO::action(fn () => 42);

        expect($io->eval())->toBe(42);
    });

    it('maps correctly over a function', function () {
        $io = IO::action(fn () => 42);

        expect($io->map(fn (int $i) => $i + 5)->eval())->toBe(47);
    });

    it('applies correctly a function', function () {
        $io = IO::action(fn () => 42);
        $ioF = IO::action(fn () => fn ($x) => $x + 5);

        expect($io->apply($ioF)->eval())->toBe(47);
    });

    it('creates pure IO as constant callables', function () {
        expect(IO::pure(42)->eval())->toBe(42);
    });

    it('binds correctly a function', function () {
        $io = IO::action(fn () => 42);
        $f = fn (int $i) => IO::action(fn () => $i + 5);

        expect($io->bind($f)->eval())->toBe(47);
    });
});
