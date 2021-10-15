<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\Either\EitherMonadThrow;
use Marcosh\LamPHPda\Instances\Maybe\MaybeMonadThrow;
use Marcosh\LamPHPda\Maybe;
use Marcosh\LamPHPda\Typeclass\MonadThrow;

/**
 * @template F of Brand
 */
// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
class Halfer
{
    /**
     * we require a MonadThrow instance to abstract the mechanism we use to report errors
     *
     * @var MonadThrow<F, string>
     */
    private MonadThrow $monadThrow;

    /**
     * @param MonadThrow<F, string>
     */
    public function __construct(MonadThrow $monadThrow)
    {
        $this->monadThrow = $monadThrow;
    }

    /**
     * @return HK1<F, int> we return an integer in the context defined by F, which has a MonadThrow instance, allowing
     *                     us to report failures
     */
    public function half(int $i)
    {
        if ($i % 2 !== 0) {
            return $this->monadThrow->throwError(sprintf('number %d is odd', $i));
        }

        return $this->monadThrow->pure($i / 2);
    }

    /**
     * we compose `half` with itself using its error reporting mechanism
     *
     * @return HK1<F, int>
     */
    public function divideByFour(int $i)
    {
        $half = $this->half($i);
        return $half->bind([$this, 'half']);
    }
}

describe('Monad throw', function () {
    describe('using Maybe', function () {
        $maybeHalfer = new Halfer(new MaybeMonadThrow());

        it('returns a wrapped integer if the input is even', function () use ($maybeHalfer) {
            expect($maybeHalfer->half(2))->toEqual(Maybe::just(1));
        });

        it('forgets the error if the input is odd', function () use ($maybeHalfer) {
            expect($maybeHalfer->half(3))->toEqual(Maybe::nothing());
        });

        it('divides by four a number multiple of four', function () use ($maybeHalfer) {
            expect($maybeHalfer->divideByFour(12))->toEqual(Maybe::just(3));
        });

        it('fails to divide by four a number multiple of two but not of four', function () use ($maybeHalfer) {
            expect($maybeHalfer->divideByFour(6))->toEqual(Maybe::nothing());
        });

        it('fails to divide by four an odd number', function () use ($maybeHalfer) {
            expect($maybeHalfer->divideByFour(3))->toEqual(Maybe::nothing());
        });
    });

    describe('using Either', function () {
        $eitherHalfer = new Halfer(new EitherMonadThrow());

        it('returns a wrapped integer if the input is even', function () use ($eitherHalfer) {
            expect($eitherHalfer->half(2))->toEqual(Either::right(1));
        });

        it('returns an error if the input is odd', function () use ($eitherHalfer) {
            expect($eitherHalfer->half(3))->toEqual(Either::left('number 3 is odd'));
        });

        it('divides by four a number multiple of four', function () use ($eitherHalfer) {
            expect($eitherHalfer->divideByFour(12))->toEqual(Either::right(3));
        });

        it('fails to divide by four a number multiple of two but not of four', function () use ($eitherHalfer) {
            expect($eitherHalfer->divideByFour(6))->toEqual(Either::left('number 3 is odd'));
        });

        it('fails to divide by four an odd number', function () use ($eitherHalfer) {
            expect($eitherHalfer->divideByFour(3))->toEqual(Either::left('number 3 is odd'));
        });
    });
});
