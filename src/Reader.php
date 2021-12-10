<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\ReaderBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\Reader\ReaderApplicative;
use Marcosh\LamPHPda\Instances\Reader\ReaderApply;
use Marcosh\LamPHPda\Instances\Reader\ReaderFunctor;
use Marcosh\LamPHPda\Instances\Reader\ReaderMonad;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultMonad;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template E
 * @template A
 *
 * @implements DefaultMonad<ReaderBrand<E>, A>
 *
 * @psalm-immutable
 */
final class Reader implements DefaultMonad
{
    /**
     * @var callable(E): A
     */
    private $action;

    /**
     * @param callable(E): A $f
     */
    private function __construct(callable $f)
    {
        $this->action = $f;
    }

    /**
     * @template B
     *
     * @param HK1<ReaderBrand<E>, callable(A): B> $f
     *
     * @return Reader<E, B>
     */
    public function apply(HK1 $f): Reader
    {
        return $this->iapply(new ReaderApply(), $f);
    }

    /**
     * @return Reader<E, E>
     */
    public function ask(): self
    {
        return self::reader(
            /**
             * @param E $e
             *
             * @return E
             */
            fn($e) => $e
        );
    }

    /**
     * @template B
     *
     * @param callable(A): HK1<ReaderBrand<E>, B> $f
     *
     * @return Reader<E, B>
     */
    public function bind(callable $f): Reader
    {
        return $this->ibind(new ReaderMonad(), $f);
    }

    /**
     * @template B
     * @template F
     *
     * @param HK1<ReaderBrand<F>, B> $b
     *
     * @return Reader<F, B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $b): Reader
    {
        /** @var Reader<F, B> $b */
        return $b;
    }

    /**
     * @template B
     *
     * @param Apply<ReaderBrand<E>> $apply
     * @param HK1<ReaderBrand<E>, callable(A): B> $f
     *
     * @return Reader<E, B>
     */
    public function iapply(Apply $apply, HK1 $f): Reader
    {
        return self::fromBrand($apply->apply($f, $this));
    }

    /**
     * @template B
     *
     * @param Monad<ReaderBrand<E>> $monad
     * @param callable(A): HK1<ReaderBrand<E>, B> $f
     *
     * @return Reader<E, B>
     */
    public function ibind(Monad $monad, callable $f): Reader
    {
        return self::fromBrand($monad->bind($this, $f));
    }

    /**
     * @template B
     *
     * @param Functor<ReaderBrand<E>> $functor
     * @param pure-callable(A): B $f
     *
     * @return Reader<E, B>
     */
    public function imap(Functor $functor, callable $f): Reader
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @template F
     *
     * @param Applicative<ReaderBrand<F>> $applicative
     * @param B $a
     *
     * @return Reader<F, B>
     *
     * @psalm-pure
     */
    public static function ipure(Applicative $applicative, $a): Reader
    {
        return self::fromBrand($applicative->pure($a));
    }

    /**
     * @template B
     *
     * @param pure-callable(A): B $f
     *
     * @return Reader<E, B>
     */
    public function map(callable $f): Reader
    {
        return $this->imap(new ReaderFunctor(), $f);
    }

    /**
     * @template B
     *
     * @param B $a
     *
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public static function pure($a): self
    {
        return self::ipure(new ReaderApplicative(), $a);
    }

    /**
     * @template B
     *
     * @param callable(E): B $f
     *
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public static function reader(callable $f): Reader
    {
        return new self($f);
    }

    /**
     * @param E $environment
     *
     * @return A
     */
    public function runReader($environment)
    {
        return ($this->action)($environment);
    }
}
