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
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @implements Monad<MaybeBrand>
 *
 * @psalm-immutable
 */
final class MaybeMonad implements Monad
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
        return (new MaybeApply())->apply($f, $a);
    }

    /**
     * @template A
     * @template B
     *
     * @param HK1<MaybeBrand, A> $a
     * @param callable(A): HK1<MaybeBrand, B> $f
     *
     * @return Maybe<B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function bind(HK1 $a, callable $f): Maybe
    {
        $maybeA = Maybe::fromBrand($a);

        return $maybeA->eval(
            Maybe::nothing(),
            /**
             * @param A $a
             *
             * @return Maybe<B>
             */
            static fn ($a) => Maybe::fromBrand($f($a))
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

    /**
     * @template A
     *
     * @param A $a
     *
     * @return Maybe<A>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function pure($a): Maybe
    {
        return (new MaybeApplicative())->pure($a);
    }
}
