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
use Marcosh\LamPHPda\Typeclass\Apply;

/**
 * @implements Apply<MaybeBrand>
 *
 * @psalm-immutable
 */
final class MaybeApply implements Apply
{

    /**
     * @template A
     * @template B
     *
     * @param HK1<MaybeBrand, callable(A): B> $f
     * @param HK1<MaybeBrand, A> $a
     *
     * @return Maybe<B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f, HK1 $a): Maybe
    {
        $maybeF = Maybe::fromBrand($f);
        $maybeA = Maybe::fromBrand($a);

        return $maybeA->eval(
            Maybe::nothing(),
            /**
             * @param A $value
             *
             * @return Maybe<B>
             */
            fn ($value) => $maybeF->eval(
                Maybe::nothing(),
                /**
                 * @psalm-param callable(A): B $g
                 * @psalm-return Maybe<B>
                 */
                fn ($g) => Maybe::just($g($value))
            )
        );
    }
    /**
     * @template A
     * @template B
     *
     * @param pure-callable(A): B $f
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
        return (new MaybeFunctor())->map($f, $a);
    }
}
