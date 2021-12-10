<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Foldable;

/**
 * @implements Foldable<EitherBrand>
 *
 * @psalm-immutable
 */
final class EitherFoldable implements Foldable
{
    /**
     * @template A
     * @template B
     * @template C
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @param HK1<EitherBrand<C>, A> $a
     * @return B
     *
     * @psalm-pure
     */
    public function foldr(callable $f, $b, HK1 $a)
    {
        $eitherA = Either::fromBrand($a);

        return $eitherA->eval(
            /**
             * @param C $_
             * @return B
             */
            fn ($_) => $b,
            /**
             * @param A $a
             * @return B
             */
            fn ($a) => $f($a, $b)
        );
    }
}
