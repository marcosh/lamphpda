<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\LinkedListBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListApply;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListFunctor;
use Marcosh\LamPHPda\Instances\LinkedList\LinkedListMonoid;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultFunctor;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Semigroup;

/**
 * @template-covariant A
 *
 * @implements DefaultFunctor<LinkedListBrand, A>
 *
 * @psalm-immutable
 */
final class LinkedList implements DefaultFunctor
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
     */
    public static function cons($head, self $tail): self
    {
        return new self(false, $head, $tail);
    }

    /**
     * @template B
     * @param list<B> $list
     * @return LinkedList<B>
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
    public function foldr(callable $op, $unit)
    {
        if ($this->isEmpty) {
            return $unit;
        }

        /**
         * @psalm-suppress ImpureFunctionCall
         * @psalm-suppress PossiblyNullArgument
         * @psalm-suppress PossiblyNullReference
         */
        return $op($this->head, $this->tail->foldr($op, $unit));
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
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return LinkedList<B>
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
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     * @param HK1<LinkedListBrand, callable(A): B> $f
     * @return LinkedList<B>
     */
    public function apply(HK1 $f): self
    {
        return $this->iapply(new LinkedListApply(), $f);
    }
}
