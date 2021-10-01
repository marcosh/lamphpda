<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Maybe;

use Marcosh\LamPHPda\Brand\MaybeBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Maybe;
use Marcosh\LamPHPda\Typeclass\Applicative;

/**
 * @implements Applicative<MaybeBrand>
 *
 * @psalm-immutable
 */
final class MaybeMonad implements Applicative
{
    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<MaybeBrand, A> $a
     * @return HK1<MaybeBrand, B>
     *
     * @psalm-pure
     */
    public function map(callable $f, $a): HK1
    {
        return Maybe::fromBrand($a)->eval(
            Maybe::nothing(),
            /**
             * @param A $value
             * @return Maybe<B>
             */
            fn($value) => Maybe::just($f($value))
        );
    }

    /**
     * @template A
     * @template B
     * @param HK1<MaybeBrand, callable(A): B> $f
     * @param HK1<MaybeBrand, A> $a
     * @return HK1<MaybeBrand, B>
     *
     * @psalm-pure
     */
    public function apply(HK1 $f, HK1 $a): HK1
    {
        $maybeF = Maybe::fromBrand($f);
        $maybeA = Maybe::fromBrand($a);

        return $maybeA->eval(
            Maybe::nothing(),
            /**
             * @param A $value
             * @return Maybe<B>
             */
            fn($value) => $maybeF->eval(
                Maybe::nothing(),
                /**
                 * @psalm-param callable(A): B $g
                 * @psalm-return Maybe<B>
                 */
                fn($g) => Maybe::just($g($value))
            )
        );
    }

    /**
     * @template A
     * @param A $a
     * @return HK1<MaybeBrand, A>
     *
     * @psalm-pure
     */
    public static function pure($a): HK1
    {
        return Maybe::just($a);
    }
}
