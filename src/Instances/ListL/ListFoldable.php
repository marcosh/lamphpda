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
    public function foldr(callable $f, $b, HK1 $a)
    {
        $aList = ListL::fromBrand($a)->asNativeList();

        if ([] === $aList) {
            return $b;
        }

        $head = array_shift($aList);

        /**
         * @psalm-suppress ImpureFunctionCall
         * @psalm-suppress ImpureVariable
         */
        return $f(
            $head,
            $this->foldr(
                $f,
                $b,
                new ListL($aList)
            )
        );
    }
}
