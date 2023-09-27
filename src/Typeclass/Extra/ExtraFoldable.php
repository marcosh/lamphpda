<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\Extra;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @template T of Brand
 *
 * @psalm-immutable
 */
final class ExtraFoldable
{
    /**
     * @param Foldable<T> $foldable
     */
    public function __construct(private readonly Foldable $foldable)
    {
    }

    /**
     * @template A
     * @template M
     * @param Monoid<M> $mMonoid
     * @param callable(A): M $f
     * @param HK1<T, A> $hk
     * @return M
     */
    public function foldMap(Monoid $mMonoid, callable $f, $hk)
    {
        return $this->foldable->foldr(
            /**
             * @param A $a
             * @param M $m
             * @return M
             */
            static fn ($a, $m) => $mMonoid->append($f($a), $m),
            $mMonoid->mempty(),
            $hk
        );
    }

    /**
     * @template M
     * @param Monoid<M> $mMonoid
     * @param HK1<T, M> $hk
     * @return M
     */
    public function fold(Monoid $mMonoid, $hk)
    {
        return $this->foldMap(
            $mMonoid,
            /**
             * @param M $m
             * @return M
             */
            static fn ($m) => $m,
            $hk
        );
    }
}
