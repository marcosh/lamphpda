<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\PairBrand;
use Marcosh\LamPHPda\Brand\PairBrand2;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\HK\HK2Covariant;
use Marcosh\LamPHPda\Instances\Pair\PairApplicative;
use Marcosh\LamPHPda\Instances\Pair\PairApply;
use Marcosh\LamPHPda\Instances\Pair\PairFunctor;
use Marcosh\LamPHPda\Instances\Pair\PairMonad;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultFunctor;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;
use Marcosh\LamPHPda\Typeclass\Monoid;
use Marcosh\LamPHPda\Typeclass\Semigroup;

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
     * @return A
     */
    public function first()
    {
        return $this->eval(
            /**
             * @param A $a
             * @param B $_
             * @return A
             */
            static fn ($a, $_) => $a
        );
    }

    /**
     * @return B
     */
    public function second()
    {
        return $this->eval(
            /**
             * @param A $_
             * @param B $b
             * @return B
             */
            static fn ($_, $b) => $b
        );
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

    /**
     * @template C
     * @param Apply<PairBrand<A>> $apply
     * @param HK1<PairBrand<A>, callable(B): C> $f
     * @return Pair<A, C>
     */
    public function iapply(Apply $apply, HK1 $f): self
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template C
     * @param Semigroup<A> $semigroup
     * @param HK1<PairBrand<A>, callable(B): C> $f
     * @return Pair<A, C>
     */
    public function apply(Semigroup $semigroup, HK1 $f): self
    {
        return $this->iapply(new PairApply($semigroup), $f);
    }

    /**
     * @template C
     * @template D
     * @param Applicative<PairBrand<C>> $applicative
     * @param D $a
     * @return Pair<C, D>
     */
    public static function ipure(Applicative $applicative, $a): self
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template C
     * @template D
     * @param Monoid<C> $monoid
     * @param D $a
     * @return Pair<C, D>
     */
    public static function pure(Monoid $monoid, $a): self
    {
        return self::ipure(new PairApplicative($monoid), $a);
    }

    /**
     * @template C
     * @param Monad<PairBrand<A>> $monad
     * @param callable(B): HK1<PairBrand<A>, C> $f
     * @return Pair<A, C>
     */
    public function ibind(Monad $monad, callable $f): self
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template C
     * @param Monoid<A> $monoid
     * @param callable(B): HK1<PairBrand<A>, C> $f
     * @return Pair<A, C>
     */
    public function bind(Monoid $monoid, callable $f): self
    {
        return $this->ibind(new PairMonad($monoid), $f);
    }
}
