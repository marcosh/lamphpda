<?php

declare(strict_types=1);

use Marcosh\LamPHPda\IO;

describe('IO', function () {
    it('evaluates correctly the callable', function () {
        $io = IO::action(fn () => 42);

        expect($io->eval())->toBe(42);
    });
});
