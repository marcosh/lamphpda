<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Maybe;

use Marcosh\LamPHPda\Brand\MaybeBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Maybe;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @implements Functor<MaybeBrand>
 *
 * @psalm-immutable
 */
final class MaybeFunctor implements Functor
{
    /**
     * @template A
     * @template B
     *
     * @param callable(A): B $f
     * @param HK1<MaybeBrand, A> $a
     *
     * @return Maybe<B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f, HK1 $a): Maybe
    {
        return Maybe::fromBrand($a)->eval(
            Maybe::nothing(),
            /**
             * @param A $value
             *
             * @return Maybe<B>
             */
            static fn ($value): Maybe => Maybe::just($f($value))
        );
    }
}
