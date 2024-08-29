<?php

declare(strict_types=1);

use Marcosh\LamPHPda\Instances\LinkedList\LinkedListMonoid;
use Marcosh\LamPHPda\Instances\OppositeMonoid;
use Marcosh\LamPHPda\Instances\OppositeSemigroup;
use Marcosh\LamPHPda\LinkedList;

describe('ListTOppositeMonoid', function () {
    it('has the same empty element', function () {
        expect((new OppositeMonoid(new LinkedListMonoid()))->mempty())->toEqual((new LinkedListMonoid())->mempty());
    });
});

describe('ListTOppositeSemigroup', function () {
    it('reverses the result of append', function () {
        expect((new OppositeSemigroup(new LinkedListMonoid()))->append(LinkedList::fromList([1]), LinkedList::fromList([2])))
            ->toEqual((new LinkedListMonoid())->append(LinkedList::fromList([2]), LinkedList::fromList([1])));
    });
});