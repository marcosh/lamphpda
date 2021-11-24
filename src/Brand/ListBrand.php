<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Brand;

use Marcosh\LamPHPda\HK\HK1;

/**
 * @implements Brand<list>
 */
final class ListBrand implements Brand
{
    /**
     * @template A
     * @param HK1<ListBrand, A> $hk
     * @return list<A>
     *
     * @psalm-pure
     */
    public static function toList(HK1 $hk): array
    {
        /** @var list<A> */
        return $hk;
    }

    /**
     * @template A
     * @param list<A> $a
     * @return HK1<ListBrand, A>
     *
     * @psalm-pure
     */
    public static function fromList(array $a): HK1
    {
        /** @var HK1<ListBrand, A> */
        return $a;
    }
}
