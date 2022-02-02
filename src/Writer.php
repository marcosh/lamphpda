<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template W
 * @template-covariant A
 *
 * @psalm-immutable
 */
final class Writer
{
    /** @var W */
    private $w;

    /** @var A */
    private $a;

    /**
     * @param W $w
     * @param A $a
     */
    private function __construct($w, $a)
    {
        $this->w = $w;
        $this->a = $a;
    }

    /**
     * @template X
     * @template B
     * @param X $w
     * @param B $a
     * @return Writer<X, B>
     */
    public static function writer($w, $a): self
    {
        return new self($w, $a);
    }

    /**
     * @return Pair<W, A>
     */
    public function runWriter(): Pair
    {
        return Pair::pair($this->w, $this->a);
    }
}
