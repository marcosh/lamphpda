<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\IOBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\IO\IOFunctor;
use Marcosh\LamPHPda\Typeclass\DefaultInstance\DefaultFunctor;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * an instance of IO<A> represents a computation which will return an instance of type A
 *
 * @template-covariant A
 *
 * @implements DefaultFunctor<IOBrand, A>
 *
 * @psalm-immutable
 */
final class IO implements DefaultFunctor
{
    /** @var callable(): A */
    private $action;

    /**
     * @param callable(): A $action
     */
    private function __construct(callable $action)
    {
        $this->action = $action;
    }

    /**
     * @template B
     * @param callable(): B $action
     * @return IO<B>
     *
     * @psalm-pure
     */
    public static function action(callable $action): self
    {
        return new self($action);
    }

    /**
     * @template B
     * @param HK1<IOBrand, B> $hk
     * @return IO<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $hk): self
    {
        /** @var IO<B> */
        return $hk;
    }

    /**
     * @return A
     */
    public function eval()
    {
        /** @psalm-suppress ImpureFunctionCall */
        return ($this->action)();
    }

    /**
     * @template B
     * @param Functor<IOBrand> $functor
     * @param callable(A): B $f
     * @return IO<B>
     */
    public function imap(Functor $functor, callable $f): self
    {
        return self::fromBrand($functor->map($f, $this));
    }

    /**
     * @template B
     * @param callable(A): B $f
     * @return IO<B>
     */
    public function map(callable $f): self
    {
        return $this->imap(new IOFunctor(), $f);
    }
}
