<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use ArrayIterator;
use Iterator;
use IteratorAggregate;
use Marcosh\LamPHPda\Brand\ListBrand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * A type wrapper around the psalm list type.
 *
 * @template-covariant A
 *
 * @implements HK1<ListBrand, A>
 *
 * @psalm-immutable
 */
final class ListL implements IteratorAggregate, HK1
{
    /**
     * @var list<A>
     */
    private array $list;

    /**
     * @param list<A> $list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * @template B
     *
     * @param HK1<ListBrand, B> $hk
     *
     * @return ListL<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $hk): ListL
    {
        /** @var ListL<B> */
        return $hk;
    }

    /**
     * @return list<A>
     */
    public function asNativeList(): array
    {
        return $this->list;
    }

    /**
     * @param A $a
     *
     * @return ListL<A>
     */
    public function append($a): self
    {
        return new self(array_merge($this->list, [$a]));
    }

    /**
     * @return Iterator<A>
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->list);
    }
}
