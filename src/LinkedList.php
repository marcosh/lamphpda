<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 * @implements Functor<A>
 */
final class LinkedList implements Functor
{
    /** @var bool */
    private $isNil;

    /**
     * @var mixed
     * @psalm-var A|null
     */
    private $head;

    /**
     * @var self|null
     * @psalm-var self<A>|null
     */
    private $tail;

    /**
     * @param bool $isNil
     * @param mixed $head
     * @psalm-param A|null $head
     * @param self|null $tail
     * @psalm-param self<A>|null $tail
     */
    private function __construct(bool $isNil, $head = null, self $tail = null)
    {
        $this->isNil = $isNil;
        $this->head = $head;
        $this->tail = $tail;
    }

    /**
     * @template B
     * @psalm-return self<B>
     */
    public static function empty(): self
    {
        return new self(true);
    }

    /**
     * @template B
     * @param mixed $head
     * @psalm-param B $head
     * @param self $tail
     * @psalm-param self<B> $tail
     * @return self
     * @psalm-return self<B>
     */
    public static function cons($head, self $tail): self
    {
        return new self(false, $head, $tail);
    }

    /**
     * @template B
     * @param callable $op
     * @psalm-param callable(A, B): B $op
     * @param mixed $unit
     * @psalm-param B $unit
     * @return mixed
     * @psalm-return B
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
     * @return self
     * @psalm-return self<B>
     */
    public function map(callable $f): self
    {
        $rec =
            /**
              * @psalm-param A $head
              * @psalm-param LinkedList<B> $tail
              * @psalm-return LinkedList<B>
              */
            fn($head, self $tail) => self::cons($f($head), $tail);

        return $this->foldr(
            $rec,
            self::empty()
        );
    }
}
