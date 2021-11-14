<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\IO;

use Marcosh\LamPHPda\Brand\IOBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\IO;
use Marcosh\LamPHPda\Typeclass\Apply;

/**
 * @implements Apply<IOBrand>
 *
 * @psalm-immutable
 */
final class IOApply implements Apply
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
    public function map(callable $f, $a): IO
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
        $ioF = IO::fromBrand($f);
        $ioA = IO::fromBrand($a);

        return IO::pure(($ioA->eval())($ioF->eval()));
    }
}
