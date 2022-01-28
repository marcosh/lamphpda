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
});
