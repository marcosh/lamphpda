<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\Brand\LinkedListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListApplicative;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListApply;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListFoldable;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListFunctor;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListMonad;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListMonoid;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListTraversable;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultMonad;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultTraversable;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;
use Marcosh\LamPHPda\Typeclass\Semigroup;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @template-covariant A
 *
 * @implements DefaultMonad<LinkedListBrand, A>
 * @implements DefaultTraversable<LinkedListBrand, A>
 *
 * @psalm-immutable
 */
final class LinkedList implements DefaultMonad, DefaultTraversable
{
    /** @var bool */
    private $isEmpty;

    /** @var null|A */
    private $head;

    /** @var null|LinkedList<A> */
    private $tail;

    /**
     * @param null|A $head
     * @param null|LinkedList<A> $tail
     */
    private function __construct(bool $isEmpty, $head = null, ?self $tail = null)
    {
        $this->isEmpty = $isEmpty;
        $this->head = $head;
        $this->tail = $tail;
    }

    /**
     * @template B
     * @return LinkedList<B>
     *
     * @psalm-pure
     */
    public static function empty(): self
    {
        return new self(true);
    }

    /**
     * @template B
     * @param B $head
     * @param LinkedList<B> $tail
     * @return LinkedList<B>
     *
     * @psalm-pure
     */
    public static function cons($head, self $tail): self
    {
        return new self(false, $head, $tail);
    }

    /**
     * @template B
     * @param list<B> $list
     * @return LinkedList<B>
     *
     * @psalm-pure
     */
    public static function fromList(array $list): self
    {
        if (empty($list)) {
            return self::empty();
        }

        return self::cons($list[0], self::fromList(\array_slice($list, 1)));
    }

    /**
     * @template B
     * @param HK1<LinkedListBrand, B> $b
     * @return LinkedList<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $b): self
    {
        /** @var LinkedList<B> */
        return $b;
    }

    /**
     * @template B
     * @param callable(A, B): B $op
     * @param B $unit
     * @return B
     */
    public function eval(callable $op, $unit)
    {
        if ($this->isEmpty) {
            return $unit;
        }

        /**
         * @psalm-suppress ImpureFunctionCall
         * @psalm-suppress PossiblyNullArgument
         * @psalm-suppress PossiblyNullReference
         */
        return $op($this->head, $this->tail->eval($op, $unit));
    }

    public function toList(): array
    {
        if ($this->isEmpty) {
            return [];
        }

        if (null === $this->tail) {
            return [$this->head];
        }

        return array_merge([$this->head], $this->tail->toList());
    }

    /**
     * @param Semigroup<LinkedList<A>> $semigroup
     * @param LinkedList<A> $that
     * @return LinkedList<A>
     */
    public function iappend(Semigroup $semigroup, self $that): self
    {
        return $semigroup->append($this, $that);
    }

    /**
     * @param LinkedList<A> $that
     * @return LinkedList<A>
     */
    public function append(self $that): self
    {
        return $this->iappend(new LinkedListMonoid(), $that);
    }

    /**
     * @template B
     * @param Functor<LinkedListBrand> $functor
     * @param callable(A): B $f
     * @return LinkedList<B>
     */
    public function imap(Functor $functor, callable $f): self
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return LinkedList<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f): self
    {
        return $this->imap(new LinkedListFunctor(), $f);
    }

    /**
     * @template B
     * @param Apply<LinkedListBrand> $apply
     * @param HK1<LinkedListBrand, callable(A): B> $f
     * @return LinkedList<B>
     */
    public function iapply(Apply $apply, HK1 $f): self
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<LinkedListBrand, callable(A): B> $f
     * @return LinkedList<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f): self
    {
        return $this->iapply(new LinkedListApply(), $f);
    }

    /**
     * @template B
     * @param Applicative<LinkedListBrand> $applicative
     * @param B $a
     * @return LinkedList<B>
     *
     * @psalm-pure
     */
    public static function ipure(Applicative $applicative, $a): self
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template B
     * @param B $a
     * @return LinkedList<B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public static function pure($a): self
    {
        return self::ipure(new LinkedListApplicative(), $a);
    }

    /**
     * @template B
     * @param Monad<LinkedListBrand> $monad
     * @param callable(A): HK1<LinkedListBrand, B> $f
     * @return LinkedList<B>
     */
    public function ibind(Monad $monad, callable $f): self
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     * @param callable(A): HK1<LinkedListBrand, B> $f
     * @return LinkedList<B>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function bind(callable $f): self
    {
        return $this->ibind(new LinkedListMonad(), $f);
    }

    /**
     * @template B
     * @param Foldable<LinkedListBrand> $foldable
     * @param callable(A, B): B $f
     * @param B $b
     * @return B
     */
    public function ifoldr(Foldable $foldable, callable $f, $b)
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return $foldable->foldr($f, $b, $this);
    }

    /**
     * @template B
     * @param callable(A, B): B $f
     * @param B $b
     * @return B
     */
    public function foldr(callable $f, $b)
    {
        return $this->ifoldr(new LinkedListFoldable(), $f, $b);
    }

    /**
     * @template F of Brand
     * @template B
     * @param Traversable<LinkedListBrand> $traversable
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @return HK1<F, LinkedList<B>>
     */
    public function itraverse(Traversable $traversable, Applicative $applicative, callable $f): HK1
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return $applicative->map([self::class, 'fromBrand'], $traversable->traverse($applicative, $f, $this));
    }

    /**
     * @template F of Brand
     * @template B
     * @param Applicative<F> $applicative
     * @param callable(A): HK1<F, B> $f
     * @return HK1<F, LinkedList<B>>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function traverse(Applicative $applicative, callable $f): HK1
    {
        return $this->itraverse(new LinkedListTraversable(), $applicative, $f);
    }
}
