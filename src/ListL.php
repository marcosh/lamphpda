<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use IteratorAggregate;
use Marcosh\LamPHPda\Brand\ListBrand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * a type wrapper around the psalm list type.
 *
 * @template-covariant A
 *
 * @implements HK1<ListBrand, A>
 * @implements IteratorAggregate<mixed, A>
 *
 * @psalm-immutable
 */
final class ListL implements \IteratorAggregate, HK1
{
    /**
     * @param list<A> $list
     */
    public function __construct(private readonly array $list)
    {
    }

    /**
     * @template B
     * @param HK1<ListBrand, B> $hk
     * @return ListL<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $hk): self
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
     * @return ListL<A>
     */
    public function prepend(mixed $a): self
    {
        return new self([...[$a], ...$this->list]);
    }

    /**
     * @return \Iterator<A>
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->list);
    }
}
