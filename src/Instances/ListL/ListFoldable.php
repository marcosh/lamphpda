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
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @param HK1<ListBrand, A> $a
     * @return B
     *
     * @psalm-pure
     */
    public function foldr(callable $f, $b, HK1 $a)
    {
        $aList = ListL::fromBrand($a);

        /** @psalm-suppress ImpureMethodCall */
        foreach ($aList as $aElement) {
            $b = $f($aElement, $b);
        }

        return $b;
    }
}
