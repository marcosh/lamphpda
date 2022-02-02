<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template-covariant A
 *
 * @psalm-immutable
 */
final class LinkedList
{
    /** @var bool */
    private $isEmpty;

    /** @var A|null */
    private $head;

    /** @var LinkedList<A>|null */
    private $tail;

    /**
     * @param A|null $head
     * @param LinkedList<A>|null $tail
     */
    private function __construct(bool $isEmpty, $head = null, ?LinkedList $tail = null)
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
    public static function empty(): LinkedList
    {
        return new self(true);
    }

    /**
     * @template B
     * @param B $head
     * @param LinkedList<B> $tail
     * @return LinkedList<B>
     */
    public static function cons($head, LinkedList $tail): LinkedList
    {
        return new self(false, $head, $tail);
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

        return $op($this->head, $this->tail->foldr($op, $unit));
    }
}
