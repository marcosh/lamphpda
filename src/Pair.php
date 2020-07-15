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
     *
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
     * @psalm-param callable(A): C $f
     * @psalm-return Pair<C,B>
     * @psalm-pure
     */
    public function lmap(callable $f): Pair
    {
        $fPair =
            /**
             * @psalm-param A $left
             * @psalm-param B $right
             * @psalm-return Pair<C,B>
             */
            static fn ($left, $right) => self::pair($f($left), $right);

        return $this->uncurry($fPair);
    }

    /**
     * @template C
     * @psalm-param callable(B): C $f
     * @psalm-return Pair<A,C>
     * @psalm-pure
     */
    public function map(callable $f): Pair
    {
        $fPair =
            /**
             * @psalm-param A $left
             * @psalm-param B $right
             * @psalm-return Pair<A,C>
             */
            static fn ($left, $right) => self::pair($left, $f($right));

        return $this->uncurry($fPair);
    }

    /**
     * @template C
     * @template D
     *
     * @param mixed $left
     * @psalm-param C $left
     *
     * @param mixed $right
     * @psalm-param D $right
     * @psalm-return Pair<C,D>
     * @psalm-pure
     */
    public static function pair($left, $right): Pair
    {
        return new self($left, $right);
    }

    /**
     * @template C
     * @psalm-param callable(A, B): C $f
     *
     * @return mixed
     * @psalm-return C
     * @psalm-pure
     */
    public function uncurry(callable $f)
    {
        return $f($this->left, $this->right);
    }
}
