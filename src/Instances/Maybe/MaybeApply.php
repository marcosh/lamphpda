<?php

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
     * @param callable(A): B $f
     * @param HK1<MaybeBrand, A> $a
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
     * @template B
     * @param HK1<MaybeBrand, callable(A): B> $f
     * @param HK1<MaybeBrand, A> $a
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
             * @return Maybe<B>
             */
            static fn (mixed $value): Maybe => $maybeF->eval(
                Maybe::nothing(),
                /**
                 * @psalm-param callable(A): B $g
                 * @psalm-return Maybe<B>
                 */
                static fn (mixed $g): Maybe => Maybe::just($g($value))
            )
        );
    }
}
