<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Reader;

use Marcosh\LamPHPda\Brand\ReaderBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Reader;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template E
 *
 * @implements Functor<ReaderBrand<E>>
 *
 * @psalm-immutable
 */
final class ReaderFunctor implements Functor
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<ReaderBrand<E>, A> $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public function map(callable $f, HK1 $a): Reader
    {
        $readerA = Reader::fromBrand($a);

        return Reader::reader(
            /**
             * @param E $env
             * @return B
             */
            static fn ($env) => $f($readerA->runReader($env))
        );
    }
}
