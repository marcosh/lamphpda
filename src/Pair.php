<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 * @template B
 */
final class Pair
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
     */
    public static function pair($left, $right)
    {
        return new self($left, $right);
    }

    /**
     * @template C
     * @param callable $f
     * @psalm-param callable(A, B): C $f
     * @return mixed
     * @psalm-return C
     */
    public function uncurry(callable $f)
    {
        return $f($this->left, $this->right);
    }
}
