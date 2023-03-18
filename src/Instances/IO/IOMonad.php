<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\IO;

use Marcosh\LamPHPda\Brand\IOBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\IO;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @implements Monad<IOBrand>
 *
 * @psalm-immutable
 */
final class IOMonad implements Monad
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
    public function pure(mixed $a): IO
    {
        return (new IOApplicative())->pure($a);
    }

    /**
     * @template A
     * @template B
     * @param HK1<IOBrand, A> $a
     * @param callable(A): HK1<IOBrand, B> $f
     * @return IO<B>
     *
     * @psalm-pure
     */
    public function bind(HK1 $a, callable $f): IO
    {
        $ioA = IO::fromBrand($a);

        return IO::action(static fn (): mixed => IO::fromBrand($f($ioA->eval()))->eval());
    }
}
