<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Traversable;

use Marcosh\LamPHPda\Brand\TraversableBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Traversable;
use Marcosh\LamPHPda\Typeclass\Foldable;

/**
 * @implements Foldable<TraversableBrand>
 *
 * @psalm-immutable
 */
final class TraversableFoldable implements Foldable
{
    /**
     * @template A
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<TraversableBrand, A> $a
     * @return B
     *
     * @psalm-pure
     */
    public function foldr(callable $f, mixed $b, HK1 $a): mixed
    {
        $arrayA = Traversable::fromBrand($a);

        $ret = $b;

        /** @psalm-suppress ImpureMethodCall */
        foreach ($arrayA as $element) {
            /** @psalm-suppress ImpureFunctionCall */
            $ret = $f($element, $ret);
        }

        return $ret;
    }
}
