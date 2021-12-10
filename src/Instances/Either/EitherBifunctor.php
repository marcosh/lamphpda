<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand2;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK2Covariant;
use Marcosh\LamPHPda\Typeclass\Bifunctor;

/**
 * @implements Bifunctor<EitherBrand2>
 *
 * @psalm-immutable
 */
final class EitherBifunctor implements Bifunctor
{
    /**
     * @template A
     * @template B
     * @template C
     * @template D
     *
     * @param callable(A): C $f
     * @param callable(B): D $g
     * @param HK2Covariant<EitherBrand2, A, B> $a
     *
     * @return Either<C, D>
     */
    public function biMap(callable $f, callable $g, HK2Covariant $a): Either
    {
        return Either::fromBrand2($a)->eval(
            /**
             * @param A $a
             *
             * @return Either<C, D>
             */
            fn ($a) => Either::left($f($a)),
            /**
             * @param B $b
             *
             * @return Either<C, D>
             */
            fn ($b) => Either::right($g($b))
        );
    }
}
