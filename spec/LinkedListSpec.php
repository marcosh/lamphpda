<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Instances\Maybe\MaybeApplicative;
use Marcosh\LamPHPda\LinkedList;
use Marcosh\LamPHPda\Maybe;
use const Marcosh\LamPHPda\Instances\Maybe\MaybeApplicative;

describe('LinkedList', function () {
    it('folds an empty list to the unit', function () {
        $list = LinkedList::empty();

        expect($list->eval(fn ($x, $y) => $x + $y, 42))->toBe(42);
    });

    it('folds a non-empty list combining the elements', function () {
        $list = LinkedList::cons(1, LinkedList::cons(2, LinkedList::cons(3, LinkedList::empty())));

        expect($list->eval(fn ($x, $y) => $x + $y, 0))->toBe(6);
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

    it('creates a singleton list', function () {
        expect(LinkedList::pure(42))->toEqual(LinkedList::fromList([42]));
    });

    it('binds a function to a linked list', function () {
        $list = LinkedList::fromList([1, 2, 3]);
        $f = fn (int $i) => LinkedList::fromList([$i + 1, $i + 42]);

        expect($list->bind($f))->toEqual(LinkedList::fromList([2, 43, 3, 44, 4, 45]));
    });

    it('traverses a linked list with an applicative', function () {
        $f = fn (int $i) => $i % 2 === 0 ? Maybe::just($i / 2) : Maybe::nothing();

        expect(LinkedList::fromList([1, 2, 3])->traverse(new MaybeApplicative(), $f))->toEqual(Maybe::nothing());
        expect(LinkedList::fromList([2, 4, 6])->traverse(new MaybeApplicative(), $f))->toEqual(Maybe::just(LinkedList::fromList([1, 2, 3])));
    });
});
