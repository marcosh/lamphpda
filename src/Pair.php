<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\PairBrand;
use Marcosh\LamPHPda\Typeclass\Foldable;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template A
 * @template B
 * @implements Functor<PairBrand, B>
 * @implements Foldable<PairBrand, B>
 * @psalm-immutable
 */
final class Pair implements Functor, Foldable
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
     * @psalm-mutation-free
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
     * @return Pair
     * @psalm-return Pair<C,D>
     * @psalm-pure
     */
    public static function pair($left, $right): Pair
    {
        return new self($left, $right);
    }

    /**
     * @template C
     * @param callable $f
     * @psalm-param callable(A, B): C $f
     * @return mixed
     * @psalm-return C
     * @psalm-mutation-free
     */
    public function uncurry(callable $f)
    {
        return $f($this->left, $this->right);
    }

    /**
     * @template C
     * @param callable $f
     * @psalm-param callable(B): C $f
     * @return Pair
     * @psalm-return Pair<A,C>
     * @psalm-mutation-free
     */
    public function map(callable $f): Pair
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
     * @param callable $op
     * @psalm-param callable(B, C): C $op
     * @param mixed $unit
     * @psalm-param C $unit
     * @return mixed
     * @psalm-return C
     * @psalm-mutation-free
     */
    public function foldr(callable $op, $unit)
    {
        return $op($this->right, $unit);
    }

    /**
     * @template C
     * @param callable $f
     * @psalm-param callable(A): C $f
     * @return Pair
     * @psalm-return Pair<C,B>
     * @psalm-mutation-free
     */
    public function lmap(callable $f): Pair
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
