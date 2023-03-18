<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Foldable;

/**
 * @template C
 *
 * @implements Foldable<EitherBrand<C>>
 *
 * @psalm-immutable
 */
final class EitherFoldable implements Foldable
{
    /**
     * @template A
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<EitherBrand<C>, A> $a
     * @return B
     *
     * @psalm-pure
     */
    public function foldr(callable $f, mixed $b, HK1 $a): mixed
    {
        $eitherA = Either::fromBrand($a);

        return $eitherA->eval(
            /**
             * @param C $_
             * @return B
             */
            static fn (mixed $_): mixed => $b,
            /**
             * @param A $a
             * @return B
             */
            static fn (mixed $a): mixed => $f($a, $b)
        );
    }
}
