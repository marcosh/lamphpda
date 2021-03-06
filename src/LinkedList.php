<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\LinkedListBrand;
use Marcosh\LamPHPda\Brand\LinkedListPBrand;
use Marcosh\LamPHPda\HK\HK0;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;
use Marcosh\LamPHPda\Typeclass\Monoid;
use Marcosh\LamPHPda\Typeclass\Semigroup;

/**
 * @template A
 * @implements Functor<LinkedListBrand, A>
 * @implements Apply<LinkedListBrand, A>
 * @implements Applicative<LinkedListBrand, A>
 * @implements Monad<LinkedListBrand, A>
 * @implements Semigroup<LinkedListPBrand<A>>
 * @implements Monoid<LinkedListPBrand<A>>
 * @implements Foldable<LinkedListBrand, A>
 * @psalm-immutable
 */
final class LinkedList implements Functor, Apply, Applicative, Monad, Semigroup, Monoid, Foldable
{
    /** @var bool */
    private $isNil;

    /**
     * @var mixed
     * @psalm-var A|null
     */
    private $head;

    /**
     * @var LinkedList|null
     * @psalm-var LinkedList<A>|null
     */
    private $tail;

    /**
     * @param bool $isNil
     * @param mixed $head
     * @psalm-param A|null $head
     * @param LinkedList|null $tail
     * @psalm-param LinkedList<A>|null $tail
     * @psalm-mutation-free
     */
    private function __construct(bool $isNil, $head = null, LinkedList $tail = null)
    {
        $this->isNil = $isNil;
        $this->head = $head;
        $this->tail = $tail;
    }

    /**
     * @template B
     * @psalm-return LinkedList<B>
     * @psalm-pure
     */
    public static function empty(): LinkedList
    {
        return new self(true);
    }

    /**
     * @template B
     * @param mixed $head
     * @psalm-param B $head
     * @param LinkedList $tail
     * @psalm-param LinkedList<B> $tail
     * @return LinkedList
     * @psalm-return LinkedList<B>
     * @psalm-pure
     */
    public static function cons($head, LinkedList $tail): LinkedList
    {
        return new self(false, $head, $tail);
    }

    /**
     * @template B
     * @param HK1 $hk
     * @psalm-param HK1<LinkedListBrand, B> $hk
     * @return LinkedList
     * @psalm-return LinkedList<B>
     * @psalm-pure
     */
    private static function fromBrand(HK1 $hk): LinkedList
    {
        /** @var LinkedList $hk */
        return $hk;
    }

    /**
     * @template B
     * @param HK0 $hk
     * @psalm-param HK0<LinkedListPBrand<B>> $hk
     * @return LinkedList
     * @psalm-return LinkedList<B>
     * @psalm-pure
     */
    private static function fromPBrand(HK0 $hk): LinkedList
    {
        /** @var LinkedList $hk */
        return $hk;
    }

    /**
     * @template B
     * @param callable $op
     * @psalm-param callable(A, B): B $op
     * @param mixed $unit
     * @psalm-param B $unit
     * @return mixed
     * @psalm-return B
     * @psalm-mutation-free
     */
    public function foldr(callable $op, $unit)
    {
        if ($this->isNil) {
            return $unit;
        }

        /**
         * @psalm-suppress PossiblyNullArgument
         * @psalm-suppress PossiblyNullReference
         */
        return $op($this->head, $this->tail->foldr($op, $unit));
    }

    /**
     * @template B
     * @param callable $f
     * @psalm-param callable(A): B $f
     * @return LinkedList
     * @psalm-return LinkedList<B>
     * @psalm-mutation-free
     */
    public function map(callable $f): LinkedList
    {
        $rec =
            /**
              * @psalm-param A $head
              * @psalm-param LinkedList<B> $tail
              * @psalm-return LinkedList<B>
              */
            fn($head, LinkedList $tail) => self::cons($f($head), $tail);

        return $this->foldr(
            $rec,
            self::empty()
        );
    }

    /**
     * @template B
     * @param Apply $f
     * @psalm-param Apply<LinkedListBrand, callable(A): B> $f
     * @return LinkedList
     * @psalm-return LinkedList<B>
     * @psalm-pure
     */
    public function apply(Apply $f): LinkedList
    {
        $f = self::fromBrand($f);

        return $f->foldr(
            /**
             * @psalm-param callable(A): B $a
             * @psalm-param LinkedList<B> $b
             * @psalm-return LinkedList<B>
             */
            function (callable $a, LinkedList $b) {
                return $this->foldr(
                    /**
                     * @psalm-param A $c
                     * @psalm-param LinkedList<B> $d
                     * @psalm-return LinkedList<B>
                     */
                    function ($c, LinkedList $d) use ($a) {
                        return self::cons($a($c), $d);
                    },
                    self::empty()
                )->append($b);
            },
            self::empty()
        );
    }

    /**
     * @template B
     * @param $a
     * @psalm-param B $a
     * @return LinkedList
     * @psalm-return LinkedList<B>
     * @psalm-pure
     */
    public static function pure($a): LinkedList
    {
        return self::cons($a, self::empty());
    }

    /**
     * @template B
     * @param callable $f
     * @psalm-param callable(A): Monad<LinkedListBrand, B> $f
     * @return LinkedList
     * @psalm-return LinkedList<B>
     * @psalm-mutation-free
     */
    public function bind(callable $f): LinkedList
    {
        return $this->foldr(
            /**
             * @psalm-param A $a
             * @psalm-param LinkedList<B> $b
             * @psalm-return LinkedList<B>
             */
            fn($a, $b) => self::fromBrand($f($a))->append($b),
            self::empty()
        );
    }

    /**
     * @param HK0 $that
     * @psalm-param HK0<LinkedListPBrand<A>> $that
     * @return LinkedList
     * @psalm-return LinkedList<A>
     * @psalm-mutation-free
     */
    public function append(HK0 $that): LinkedList
    {
        $that = self::fromPBrand($that);

        return $this->foldr(
            /**
             * @psalm-param A $a
             * @psalm-param LinkedList<A> $b
             * @psalm-return LinkedList<A>
             */
            function ($a, LinkedList $b) {
                return self::cons($a, $b);
            },
            $that
        );
    }

    /**
     * @template B
     * @psalm-return LinkedList<B>
     * @psalm-pure
     */
    public static function mempty(): LinkedList
    {
        return self::empty();
    }

    /**
     * @return bool
     * @psalm-mutation-free
     */
    public function isEmpty(): bool
    {
        return $this->foldr(
            /**
             * @psalm-param A $head
             * @psalm-param bool $tail
             * @psalm-return bool
             */
            fn($head, bool $tail) => false,
            true
        );
    }
}
