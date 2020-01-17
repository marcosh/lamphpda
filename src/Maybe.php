<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template A
 */
final class Maybe
{
    /** @var bool */
    private $isJust;

    /**
     * @var mixed
     * @psalm-var A|null
     */
    private $value = null;

    /**
     * @param bool $isJust
     * @param mixed $value
     * @psalm-param A|null $value
     */
    private function __construct(bool $isJust, $value = null)
    {
        $this->isJust = $isJust;
        $this->value = $value;
    }

    /**
     * @template B
     * @param mixed $value
     * @psalm-param B $value
     * @return self
     * @psalm-return self<B>
     */
    public static function just($value): self
    {
        return new self(true, $value);
    }

    /**
     * @template B
     * @return self
     * @psalm-return self<B>
     */
    public static function nothing(): self
    {
        return new self(false);
    }

    /**
     * @template B
     * @template C
     * @param mixed $ifNothing
     * @psalm-param C $ifNothing
     * @param callable $ifJust
     * @psalm-param callable(B): C $ifJust
     * @return mixed
     * @psalm-return C
     */
    public function eval(
        $ifNothing,
        callable $ifJust
    ) {
        if ($this->isJust) {
            /** @psalm-suppress PossiblyInvalidArgument */
            return $ifJust($this->value);
        }

        return $ifNothing;
    }
}
