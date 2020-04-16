<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\PairBrand;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template A
 * @template B
 * @implements Functor<PairBrand, B>
 * @psalm-immutable
 */
final class Pair implements Functor
{
    /**
     * @var mixed
     * @psalm-var A
     */
    private $left;

    /**
     * @var mixed
     * @psalm-var B
     */
    private $right;

    /**
     * @param mixed $left
     * @psalm-param A $left
     * @param mixed $right
     * @psalm-param B $right
     * @psalm-pure
     */
    private function __construct($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @template C
     * @template D
     * @param mixed $left
     * @psalm-param C $left
     * @param mixed $right
     * @psalm-param D $right
     * @return self
     * @psalm-return self<C,D>
     * @psalm-pure
     */
    public static function pair($left, $right): self
    {
        return new self($left, $right);
    }

    /**
     * @template C
     * @param callable $f
     * @psalm-param callable(A, B): C $f
     * @return mixed
     * @psalm-return C
     * @psalm-pure
     */
    public function uncurry(callable $f)
    {
        return $f($this->left, $this->right);
    }

    /**
     * @template C
     * @param callable $f
     * @psalm-param callable(B): C $f
     * @return self
     * @psalm-return self<A,C>
     * @psalm-pure
     */
    public function map(callable $f): self
    {
        $fPair =
            /**
             * @psalm-param A $left
             * @psalm-param B $right
             * @psalm-return Pair<A,C>
             */
            fn($left, $right) => self::pair($left, $f($right));

        return $this->uncurry($fPair);
    }

    /**
     * @template C
     * @param callable $f
     * @psalm-param callable(A): C $f
     * @return self
     * @psalm-return self<C,B>
     * @psalm-pure
     */
    public function lmap(callable $f): self
    {
        $fPair =
            /**
             * @psalm-param A $left
             * @psalm-param B $right
             * @psalm-return Pair<C,B>
             */
            fn($left, $right) => self::pair($f($left), $right);

        return $this->uncurry($fPair);
    }
}
