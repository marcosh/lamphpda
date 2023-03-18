<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use IteratorAggregate;
use Marcosh\LamPHPda\Brand\TraversableBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\Traversable\TraversableFoldable;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultFoldable;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Traversable as PhpTraversable;

/**
 * @template-covariant A
 *
 * @implements DefaultFoldable<TraversableBrand, A>
 * @implements IteratorAggregate<mixed, A>
 *
 * @psalm-immutable
 */
final class Traversable implements \IteratorAggregate, DefaultFoldable
{
    /**
     * @param PhpTraversable<A> $traversable
     */
    private function __construct(private readonly PhpTraversable $traversable)
    {
    }

    /**
     * @template B
     * @param PhpTraversable<B> $traversable
     * @return Traversable<B>
     *
     * @psalm-pure
     */
    public static function fromPhpTraversable(PhpTraversable $traversable): self
    {
        return new self($traversable);
    }

    /**
     * @template B
     * @param array<B> $array
     * @return Traversable<B>
     *
     * @psalm-pure
     */
    public static function fromArray(array $array): self
    {
        /** @psalm-suppress ImpureMethodCall */
        return new self(new \ArrayIterator($array));
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
     * @param callable(A, B): B $f
     * @param B $b
     * @return B
     *
     * @psalm-mutation-free
     */
    public function ifoldr(Foldable $foldable, callable $f, mixed $b): mixed
    {
        return $foldable->foldr($f, $b, $this);
    }

    /**
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @return B
     *
     * @psalm-mutation-free
     */
    public function foldr(callable $f, mixed $b): mixed
    {
        return $this->ifoldr(new TraversableFoldable(), $f, $b);
    }
}
