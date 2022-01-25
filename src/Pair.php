<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\PairBrand;
use Marcosh\LamPHPda\Brand\PairBrand2;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\HK\HK2Covariant;
use Marcosh\LamPHPda\Instances\Pair\PairFunctor;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultFunctor;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template-covariant A
 * @template-covariant B
 *
 * @implements DefaultFunctor<PairBrand<A>, B>
 * @implements HK2Covariant<PairBrand2, A, B>
 *
 * @psalm-immutable
 */
final class Pair implements DefaultFunctor, HK2Covariant
{
    /** @var A */
    private $left;

    /** @var B */
    private $right;

    /**
     * @param A $left
     * @param B $right
     */
    private function __construct($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @template C
     * @template D
     * @param C $left
     * @param D $right
     * @return Pair<C, D>
     *
     * @psalm-pure
     */
    public static function pair($left, $right): self
    {
        return new self($left, $right);
    }

    /**
     * @template C
     * @template D
     * @param HK1<PairBrand<C>, D> $hk
     * @return Pair<C, D>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $hk): self
    {
        /** @var Pair<C, D> */
        return $hk;
    }

    /**
     * @template C
     * @template D
     * @param HK2Covariant<PairBrand2, C, D> $hk
     * @return Pair<C, D>
     *
     * @psalm-pure
     */
    public static function fromBrand2(HK2Covariant $hk): self
    {
        /** @var Pair<C, D> */
        return $hk;
    }

    /**
     * @template C
     * @param callable(A, B): C $f
     * @return C
     */
    public function eval(callable $f)
    {
        /** @psalm-suppress ImpureFunctionCall */
        return $f($this->left, $this->right);
    }

    /**
     * @template C
     * @param Functor<PairBrand> $functor
     * @param callable(B): C $f
     * @return Pair<A, C>
     */
    public function imap(Functor $functor, callable $f): self
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template C
     * @param callable(B): C $f
     * @return Pair<A, C>
     */
    public function map(callable $f): self
    {
        return $this->imap(new PairFunctor(), $f);
    }
}
