<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\LinkedList;

use Marcosh\LamPHPda\LinkedList;
use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @template A
 *
 * @implements Monoid<LinkedList<A>>
 *
 * @psalm-immutable
 */
final class LinkedListMonoid implements Monoid
{
    /**
     * @return LinkedList<A>
     */
    public function mempty(): LinkedList
    {
        return LinkedList::empty();
    }

    /**
     * @param LinkedList<A> $a
     * @param LinkedList<A> $b
     * @return LinkedList<A>
     */
    public function append($a, $b): LinkedList
    {
        return $b->eval(
            /**
             * @param A $element,
             * @param LinkedList<A> $list
             * @return LinkedList<A>
             */
            static fn (mixed $element, LinkedList $list): LinkedList => LinkedList::cons($element, $list),
            $a
        );
    }
}
