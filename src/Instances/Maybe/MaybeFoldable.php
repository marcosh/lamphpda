<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Maybe;

use Marcosh\LamPHPda\Brand\MaybeBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Maybe;
use Marcosh\LamPHPda\Typeclass\Foldable;

/**
 * @implements Foldable<MaybeBrand>
 *
 * @psalm-immutable
 */
final class MaybeFoldable implements Foldable
{
    /**
     * @template A
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<MaybeBrand, A> $a
     * @return B
     */
    public function foldr(callable $f, mixed $b, HK1 $a): mixed
    {
        $maybeA = Maybe::fromBrand($a);

        return $maybeA->eval(
            $b,
            /**
             * @param A $a
             * @return B
             */
            static fn (mixed $a): mixed => $f($a, $b)
        );
    }
}
