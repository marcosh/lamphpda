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
     * @var A|null
     */
    private $value = null;

    /**
     * @param bool $isJust
     * @param A|null $value
     * @psalm-mutation-free
     */
    private function __construct(bool $isJust, $value = null)
    {
        $this->isJust = $isJust;
        $this->value = $value;
    }

    /**
     * @template B
     * @param B $value
     * @return Maybe<B>
     * @psalm-pure
     */
    public static function just($value): Maybe
    {
        return new self(true, $value);
    }

    /**
     * @psalm-pure
     */
    public static function nothing(): Maybe
    {
        return new self(false);
    }

    /**
     * @template B
     * @param B $ifNothing
     * @param callable(A): B $ifJust
     * @return B
     * @psalm-mutation-free
     */
    public function eval(
        $ifNothing,
        callable $ifJust
    ) {
        if ($this->isJust) {
            /** @psalm-suppress PossiblyNullArgument */
            return $ifJust($this->value);
        }

        return $ifNothing;
    }
}
