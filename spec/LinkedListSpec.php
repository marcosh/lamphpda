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
});
