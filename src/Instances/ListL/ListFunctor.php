<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\ListL;

use Marcosh\LamPHPda\Brand\ListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\ListL;
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
     *
     * @param pure-callable(A): B $f
     * @param HK1<ListBrand, A> $a
     *
     * @return ListL<B>
     *
     * @psalm-pure
     */
    public function map(callable $f, HK1 $a): HK1
    {
        $aList = ListL::fromBrand($a);

        $bList = [];

        /** @psalm-suppress ImpureMethodCall */
        foreach ($aList as $aElement) {
            $bList[] = $f($aElement);
        }

        return new ListL($bList);
    }
}
