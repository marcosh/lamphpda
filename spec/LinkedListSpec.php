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

    it('builds a linked list from a PHP list and goes back', function () {
        $list = LinkedList::fromList([1, 2, 3]);

        expect($list->toList())->toEqual([1, 2, 3]);
    });

    it('maps a function over every element of the list', function () {
        $list = LinkedList::fromList([1, 2, 3]);

        expect($list->map(fn ($x) => $x * 2))->toEqual(LinkedList::fromList([2, 4, 6]));
    });

    it('applies a list of functions to a list of values', function () {
        $listOfFunctions = LinkedList::fromList([fn ($x) => $x + 1, fn ($x) => $x * 2]);
        $listOfValues = LinkedList::fromList([1, 2, 3]);

        expect($listOfValues->apply($listOfFunctions))->toEqual(LinkedList::fromList([2, 3, 4, 2, 4, 6]));
    });
});
