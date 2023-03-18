<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\ListL;

use Marcosh\LamPHPda\Brand\ListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\ListL;
use Marcosh\LamPHPda\Typeclass\Foldable;

/**
 * @implements Foldable<ListBrand>
 *
 * @psalm-immutable
 */
final class ListFoldable implements Foldable
{
    /**
     * @template A
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @param HK1<ListBrand, A> $a
     * @return B
     *
     * @psalm-pure
     */
    public function foldr(callable $f, mixed $b, HK1 $a): mixed
    {
        $aList = ListL::fromBrand($a);

        /** @psalm-suppress ImpureMethodCall */
        foreach ($aList as $aElement) {
            /** @psalm-suppress ImpureFunctionCall */
            $b = $f($aElement, $b);
        }

        return $b;
    }
}
