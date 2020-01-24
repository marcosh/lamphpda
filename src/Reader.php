<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template R
 * @template A
 */
final class Reader
{
    /**
     * @var callable
     * @psalm-var callable(R): A
     */
    private $runReader;

    /**
     * @param callable $runReader
     * @psalm-param callable(R): A $runReader
     */
    private function __construct(callable $runReader)
    {
        $this->runReader = $runReader;
    }

    /**
     * @template S
     * @template B
     * @param callable $runReader
     * @psalm-param callable(S): B $runReader
     * @return self
     * @psalm-return self<S, B>
     */
    public static function reader(callable $runReader): self
    {
        return new self($runReader);
    }

    /**
     * @param mixed $state
     * @psalm-param R $state
     * @return mixed
     * @psalm-return A
     */
    public function runReader($state)
    {
        return ($this->runReader)($state);
    }
}
