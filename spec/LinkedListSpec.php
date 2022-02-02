<?php

declare(strict_types=1);

use Marcosh\LamPHPda\LinkedList;

describe('LinkedList', function () {
    it('folds an empty list to the unit', function () {
        $list = LinkedList::empty();

        expect($list->foldr(fn ($x, $y) => $x + $y, 42))->toBe(42);
    });

    it('folds a non-empty list combining the elements', function () {
        $list = LinkedList::cons(1, LinkedList::cons(2, LinkedList::cons(3, LinkedList::empty())));

        expect($list->foldr(fn ($x, $y) => $x + $y, 0))->toBe(6);
    });

    it('builds a linked list from a PHP list', function () {
        $list = [1, 2, 3];

        expect(LinkedList::fromList($list))->toEqual(
            LinkedList::cons(1, LinkedList::cons(2, LinkedList::cons(3, LinkedList::empty())))
        );
    });
});
