<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use ArrayIterator;
use IteratorAggregate;
use Marcosh\LamPHPda\Brand\TraversableBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\Traversable\TraversableFoldable;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultFoldable;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Traversable as PhpTraversable;

/**
 * @template A
 * @implements DefaultFoldable<TraversableBrand, A>
 *
 * @psalm-immutable
 */
final class Traversable implements IteratorAggregate, DefaultFoldable
{
    /** @var PhpTraversable<A> */
    private $traversable;

    /**
     * @param PhpTraversable<A> $traversable
     */
    private function __construct(PhpTraversable $traversable)
    {
        $this->traversable = $traversable;
    }

    /**
     * @template B
     * @param PhpTraversable<B> $traversable
     * @return Traversable<B>
     */
    public static function fromPhpTraversable(PhpTraversable $traversable): self
    {
        return new self($traversable);
    }

    /**
     * @template B
     * @param array<B> $array
     * @return Traversable<B>
     */
    public static function fromArray(array $array): self
    {
        return new self(new ArrayIterator($array));
    }

    /**
     * @return PhpTraversable<A>
     */
    public function getIterator(): PhpTraversable
    {
        return $this->traversable;
    }

    /**
     * @template B
     * @param HK1<TraversableBrand, B> $hk
     * @return Traversable<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $hk): self
    {
        /** @var Traversable<B> */
        return $hk;
    }

    /**
     * @template B
     * @param Foldable<TraversableBrand> $foldable
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @return B
     *
     * @psalm-mutation-free
     */
    public function ifoldr(Foldable $foldable, callable $f, $b)
    {
        return $foldable->foldr($f, $b, $this);
    }

    /**
     * @template B
     * @param pure-callable(A, B): B $f
     * @param B $b
     * @return B
     *
     * @psalm-mutation-free
     */
    public function foldr(callable $f, $b)
    {
        return $this->ifoldr(new TraversableFoldable(), $f, $b);
    }
}
