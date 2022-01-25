<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Reader;

use Marcosh\LamPHPda\Brand\ReaderBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Reader;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template E
 *
 * @implements Monad<ReaderBrand<E>>
 *
 * @psalm-immutable
 */
final class ReaderMonad implements Monad
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<ReaderBrand<E>, A> $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function map(callable $f, HK1 $a): Reader
    {
        return (new ReaderFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<ReaderBrand<E>, callable(A): B> $f
     * @param HK1<ReaderBrand<E>, A> $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function apply(HK1 $f, HK1 $a): Reader
    {
        return (new ReaderApply())->apply($f, $a);
    }

    /**
     * @template A
     * @param A $a
     * @return Reader<E, A>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function pure($a): Reader
    {
        return (new ReaderApplicative())->pure($a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<ReaderBrand<E>, A> $a
     * @param callable(A): HK1<ReaderBrand<E>, B> $f
     * @return Reader<E, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function bind(HK1 $a, callable $f): Reader
    {
        $readerA = Reader::fromBrand($a);

        return Reader::reader(
            /**
             * @param E $env
             */
            static fn ($env) => Reader::fromBrand($f($readerA->runReader($env)))->runReader($env)
        );
    }
}
