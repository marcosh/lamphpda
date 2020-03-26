<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 * @template W
 * @implements Functor<A>
 */
final class Writer implements Functor
{
    /**
     * @var Pair
     * @psalm-var Pair<A,W>
     */
    private $runWriter;

    /**
     * @param Pair $runWriter
     * @psalm-param Pair<A,W> $runWriter
     */
    private function __construct(Pair $runWriter)
    {
        $this->runWriter = $runWriter;
    }

    /**
     * @template B
     * @template X
     * @param Pair $runWriter
     * @psalm-param Pair<B,X> $runWriter
     * @return self
     * @psalm-return self<B,X>
     */
    public static function writer(Pair $runWriter): self
    {
        return new self($runWriter);
    }

    /**
     * @return Pair
     * @psalm-return Pair<A, W>
     */
    public function exec(): Pair
    {
        return $this->runWriter;
    }

    /**
     * @template B
     * @param callable $f
     * @param callable(A): B $f
     * @return self
     * @psalm-return self<B,W>
     */
    public function map(callable $f): self
    {
        return self::writer($this->runWriter->lmap($f));
    }
}
