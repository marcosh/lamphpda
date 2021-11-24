<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\ListL;

use Marcosh\LamPHPda\Brand\ListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @implements Functor<ListBrand>
 *
 * @psalm-immutable
 */
final class ListFunctor implements Functor
{
    /**
     * @template A
     * @template B
     * @param pure-callable(A): B $f
     * @param HK1<ListBrand, A> $a
     * @return HK1<ListBrand, B>
     *
     * @psalm-pure
     */
    public function map(callable $f, HK1 $a): HK1
    {
        $aList = ListBrand::toList($a);

        return ListBrand::fromList(array_map($f, $aList));
    }
}
