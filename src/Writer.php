<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\WriterBrand;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template A
 * @template W
 * @implements Functor<WriterBrand, A>
 * @psalm-immutable
 */
final class Writer implements Functor
{
    /**
     * @var Pair
     * @psalm-var Pair<A,W>
     */
    private $runWriter;

    /**
     * @psalm-param Pair<A, W> $runWriter
     * @psalm-pure
     */
    private function __construct(Pair $runWriter)
    {
        $this->runWriter = $runWriter;
    }

    /**
     * @psalm-return Pair<A, W>
     * @psalm-pure
     */
    public function exec(): Pair
    {
        return $this->runWriter;
    }

    /**
     * @template B
     * @psalm-param callable(A): B $f
     * @psalm-return Writer<B, W>
     * @psalm-pure
     */
    public function map(callable $f): Writer
    {
        return self::writer($this->runWriter->lmap($f));
    }

    /**
     * @template B
     * @template X
     * @psalm-param Pair<B, X> $runWriter
     * @psalm-return Writer<B, X>
     * @psalm-pure
     */
    public static function writer(Pair $runWriter): Writer
    {
        return new self($runWriter);
    }
}
