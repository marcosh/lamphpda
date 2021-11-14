<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\IO;

use Marcosh\LamPHPda\Brand\IOBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\IO;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @implements Functor<IOBrand>
 *
 * @psalm-immutable
 */
final class IOFunctor implements Functor
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
        return IO::pure(
            $f(IO::fromBrand($a)->eval())
        );
    }
}
