<?php

declare(strict_types=1);

namespace Marcosh\LamPHPdaSpec;

use Marcosh\LamPHPda\LinkedList;

describe('LinkedList', function () {
    it('reduces to the unit when empty', function () {
        $list = LinkedList::empty();

        $result = $list->foldr(
            (fn($a, $b) => $a + $b),
            0
        );

        expect($result)->toEqual(0);
    });

    it('uses correctly the binary operation to fold a nonempty list', function () {
        $list = LinkedList::cons("a", LinkedList::cons("b", LinkedList::cons("c", LinkedList::empty())));

        $result = $list->foldr(
            (fn($a, $b) => $a . $b),
            ""
        );

        expect($result)->toEqual("abc");
    });

    it('appends two lists correctly', function () {
        $list1 = LinkedList::cons("a", LinkedList::cons("b", LinkedList::empty()));
        $list2 = LinkedList::cons("c", LinkedList::cons("d", LinkedList::empty()));

        $expected = LinkedList::cons("a", LinkedList::cons("b", LinkedList::cons("c", LinkedList::cons("d", LinkedList::empty()))));

        expect($list1->append($list2))->toEqual($expected);
    });

    it('maps the empty list to the empty list', function () {
        $list = LinkedList::empty();

        $result = $list->map(
            (fn($a) => $a * 2)
        );

        expect($result)->toEqual(LinkedList::empty());
    });

    it('maps a non empty list applying the function to each element', function () {
        $list = LinkedList::cons(0, LinkedList::cons(1, LinkedList::cons(2, LinkedList::empty())));

        $result = $list->map(
            fn($a) => $a * 2
        );

        expect($result)->toEqual(
            LinkedList::cons(0, LinkedList::cons(2, LinkedList::cons(4, LinkedList::empty())))
        );
    });

    it('detects empty lists', function () {
        $list = LinkedList::empty();

        expect($list->isEmpty())->toBeTruthy();
    });

    it('detects non-empty lists', function () {
        $list = LinkedList::cons(0, LinkedList::cons(1, LinkedList::cons(2, LinkedList::empty())));

        expect($list->isEmpty())->toBeFalsy();
    });

    it('applies a list of functions to a list of values', function () {
        $functions = LinkedList::cons(fn($x) => $x * 2, LinkedList::cons(fn($x) => $x / 2, LinkedList::empty()));
        $values = LinkedList::cons(42, LinkedList::cons(666, LinkedList::empty()));

        $expected = LinkedList::cons(84, LinkedList::cons(1332, LinkedList::cons(21, LinkedList::cons(333, LinkedList::empty()))));

        expect($values->apply($functions))->toEqual($expected);
    });

    it('creates a singleton as pure', function () {
        expect(LinkedList::pure(42))->toEqual(LinkedList::cons(42, LinkedList::empty()));
    });

    it('binds a callable returning an empty list to an empty list to return an empty list', function () {
        $list = LinkedList::empty();
        $f = fn($x) => LinkedList::empty();

        expect($list->bind($f))->toEqual(LinkedList::empty());
    });

    it('binds a callable returning a non-empty list to an empty list to return an empty list', function () {
        $list = LinkedList::empty();
        $f = fn($x) => LinkedList::cons(42, LinkedList::empty());

        expect($list->bind($f))->toEqual(LinkedList::empty());
    });

    it('binds a callable returning an empty list to a non-empty list to return a non-empty list', function () {
        $list = LinkedList::cons(42, LinkedList::cons(13, LinkedList::empty()));
        $f = fn($x) => LinkedList::empty();

        expect($list->bind($f))->toEqual(LinkedList::empty());
    });

    it('binds a callable returning a non-empty list to a non-empty list to return a non-empty list', function () {
        $list = LinkedList::cons(42, LinkedList::cons(13, LinkedList::empty()));
        $f = fn($x) => LinkedList::cons($x + 2, LinkedList::cons($x * 2, LinkedList::empty()));

        expect($list->bind($f))->toEqual(LinkedList::cons(44, LinkedList::cons(84, LinkedList::cons(15, LinkedList::cons(26, LinkedList::empty())))));
    });

    it('appends two empty lists to get an empty list', function () {
        expect(LinkedList::empty()->append(LinkedList::empty()))->toEqual(LinkedList::empty());
    });

    it('appends two non-empty lists to get a non-empty list', function () {
        $list1 = LinkedList::cons(42, LinkedList::cons(13, LinkedList::empty()));
        $list2 = LinkedList::cons(17, LinkedList::cons(79, LinkedList::empty()));
        $result = LinkedList::cons(42, LinkedList::cons(13, LinkedList::cons(17, LinkedList::cons(79, LinkedList::empty()))));

        expect($list1->append($list2))->toEqual($result);
    });

    it('respects the associativity of append', function () {
        $list1 = LinkedList::cons(42, LinkedList::cons(13, LinkedList::empty()));
        $list2 = LinkedList::cons(17, LinkedList::cons(79, LinkedList::empty()));
        $list3 = LinkedList::cons(34, LinkedList::cons(52, LinkedList::empty()));

        expect($list1->append($list2)->append($list3))->toEqual($list1->append($list2->append($list3)));
    });

    it('respects left-identity', function () {
        $list = LinkedList::cons(42, LinkedList::cons(13, LinkedList::empty()));

        expect(LinkedList::empty()->append($list))->toEqual($list);
    });

    it('respects right-identity', function () {
        $list = LinkedList::cons(42, LinkedList::cons(13, LinkedList::empty()));

        expect($list->append(LinkedList::empty()))->toEqual($list);
    });
});
