<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 * @template B
 *
 * @psalm-immutable
 */
final class Pair
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
     * @param callable(A, B): C $f
     * @return C
     */
    public function eval(callable $f)
    {
        return $f($this->left, $this->right);
    }
}
