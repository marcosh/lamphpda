<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Reader;

use Marcosh\LamPHPda\Brand\ReaderBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Reader;
use Marcosh\LamPHPda\Typeclass\Apply;

/**
 * @implements Apply<ReaderBrand>
 *
 * @psalm-immutable
 */
final class ReaderApply implements Apply
{
    /**
     * @template A
     * @template B
     * @template E
     * @param callable(A): B $f
     * @param HK1<ReaderBrand, A> $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public function map(callable $f, $a): Reader
    {
        return (new ReaderFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @template E
     * @param HK1<ReaderBrand, callable(A): B> $f
     * @param HK1<ReaderBrand, A> $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public function apply(HK1 $f, HK1 $a): Reader
    {
        $readerF = Reader::fromBrand($f);
        $readerA = Reader::fromBrand($a);

        return Reader::reader(
            /**
             * @param E $env
             * @return B
             */
            static fn($env) => ($readerA->runReader($env))($readerF->runReader($env))
        );
    }
}
