<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Reader;

use Marcosh\LamPHPda\Brand\ReaderBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Reader;
use Marcosh\LamPHPda\Typeclass\Applicative;

/**
 * @template E
 *
 * @implements Applicative<ReaderBrand<E>>
 *
 * @psalm-immutable
 */
final class ReaderApplicative implements Applicative
{
    /**
     * @template A
     * @template B
     * @param pure-callable(A): B $f
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
        return Reader::reader(static fn ($_) => $a);
    }
}
