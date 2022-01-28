<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\IOBrand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * an instance of IO<A> represents a computation which will return an instance of type A
 *
 * @template-covariant A
 *
 * @psalm-immutable
 */
final class IO
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
}
