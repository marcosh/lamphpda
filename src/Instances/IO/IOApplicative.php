<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\IO;

use Marcosh\LamPHPda\Brand\IOBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\IO;
use Marcosh\LamPHPda\Typeclass\Applicative;

/**
 * @implements Applicative<IOBrand>
 *
 * @psalm-immutable
 */
final class IOApplicative implements Applicative
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<IOBrand, A> $a
     * @return IO<B>
     *
     * @psalm-pure
     */
    public function map(callable $f, HK1 $a): IO
    {
        return (new IOFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<IOBrand, callable(A): B> $f
     * @param HK1<IOBrand, A> $a
     * @return IO<B>
     *
     * @psalm-pure
     */
    public function apply(HK1 $f, HK1 $a): IO
    {
        return (new IOApply())->apply($f, $a);
    }

    /**
     * @template A
     * @param A $a
     * @return IO<A>
     *
     * @psalm-pure
     */
    public function pure($a): IO
    {
        return IO::action(static fn () => $a);
    }
}
