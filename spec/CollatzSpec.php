<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\Pair;
use Marcosh\LamPHPda\State;

// consider the function used to define the [Collatz conjecture](https://www.wikiwand.com/en/Collatz_conjecture)

function collatz(int $i): int {
    if ($i % 2 === 0) {
        return $i / 2;
    }

    return 3 * $i + 1;
}

// to keep track of how many steps we need to do to reach 1, we introduce a state consisting of the number of moves
//
// moreover, we use `Either` to keep track whether we already reached 1, and in that case we use a `Left` containing
// the number of moves, or not, and in that case we use a `Right` with the result of the last performed step

/**
 * @psalm-type Counter = int
 * @param int
 * @return State<Counter, Either<Counter, int>>
 */
function collatzCounter(int $i): State {
    if ($i <= 1) {
        return State::state(fn ($s) => Pair::pair($s, Either::left($s)));
    }

    return State::state(fn ($s) => Pair::pair($s + 1, Either::right(collatz($i))));
}

// we iterate our `collatzCounter` a given amount of times by recursively calling it

function iterateCollatzCounter($n) {
    if ($n <= 1) {
        return fn ($i) => collatzCounter($i);
    }

    return fn ($i) => iterateCollatzCounter($n - 1)($i)->bind(
        fn (Either $eitherCounterInt) => $eitherCounterInt->eval(
            fn ($counter) => State::pure(Either::left($counter)),
            fn ($i) => collatzCounter($i)
        )
    );
}

describe('Collatz counter', function () {
    it('computes one iteration for 5 arriving at 16', function () {
        expect(iterateCollatzCounter(1)(5)->runState(0))->toEqual(Pair::pair(1, Either::right(16)));
    });

    it('computes one iteration for 1 returning 0', function () {
        expect(iterateCollatzCounter(1)(1)->runState(0))->toEqual(Pair::pair(0, Either::left(0)));
    });

    it('computes two iterations for 5 arriving at 8', function () {
        expect(iterateCollatzCounter(2)(5)->runState(0))->toEqual(Pair::pair(2, Either::right(8)));
    });

    it('computes two iterations for 1 returning 0', function () {
        expect(iterateCollatzCounter(2)(1)->runState(0))->toEqual(Pair::pair(0, Either::left(0)));
    });

    // 5 -> 16 -> 8 -> 4 -> 2 -> 1
    it('computes ten iterations for 5 returning 5', function () {
        expect(iterateCollatzCounter(10)(5)->runState(0))->toEqual(Pair::pair(5, Either::left(5)));
    });

    it('computes ten iterations for 1 returning 0', function () {
        expect(iterateCollatzCounter(10)(1)->runState(0))->toEqual(Pair::pair(0, Either::left(0)));
    });

    // 7 -> 22 -> 11 -> 34 -> 17 -> 52 -> 26 -> 13 -> 40 -> 20 -> 10
    it('computes ten iterations for 7 arriving at 10', function () {
        expect(iterateCollatzCounter(10)(7)->runState(0))->toEqual(Pair::pair(10, Either::right(10)));
    });

    // 7 -> 22 -> 11 -> 34 -> 17 -> 52 -> 26 -> 13 -> 40 -> 20 -> 10 -> 5 -> 16 -> 8 -> 4 -> 2 -> 1
    it('computes twenty iterations for 7 returning 16', function () {
        expect(iterateCollatzCounter(20)(7)->runState(0))->toEqual(Pair::pair(16, Either::left(16)));
    });
});
