<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\MaybeBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template A
 * @implements HK1<MaybeBrand, A>
 */
final class Maybe implements HK1
{
    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $isJust;

    /**
     * @var A|null
     *
     * @psalm-readonly
     */
    private $value = null;

    /**
     * @param bool $isJust
     * @param A|null $value
     *
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
     *
     * @psalm-pure
     */
    public static function just($value): Maybe
    {
        return new self(true, $value);
    }

    /**
     * @template B
     * @return Maybe<B>
     *
     * @psalm-pure
     */
    public static function nothing(): Maybe
    {
        return new self(false);
    }

    /**
     * @template B
     * @param HK1<MaybeBrand, B> $b
     * @return Maybe<B>
     *
     * @psalm-pure
     */
    public static function fromBrand(HK1 $b): Maybe
    {
        /** @var Maybe $b */
        return $b;
    }

    /**
     * @template B
     * @param B $ifNothing
     * @param callable(A): B $ifJust
     * @return B
     *
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

    /**
     * @template B
     * @param Functor<MaybeBrand> $functor
     * @param callable(A): B $f
     * @return Maybe<B>
     *
     * @psalm-mutation-free
     */
    public function map(Functor $functor, callable $f): Maybe
    {
        /** @var Maybe<B> $maybeB */
        $maybeB = $functor->map($f, $this);

        return $maybeB;
    }

    /**
     * @template B
     * @param Apply<MaybeBrand> $apply
     * @param Maybe<callable(A): B> $f
     * @return Maybe<B>
     *
     * @psalm-mutation-free
     */
    public function apply(Apply $apply, Maybe $f): Maybe
    {
        /**
         * @var Maybe<B> $maybeB
         * @psalm-suppress MixedArgumentTypeCoercion
         * @psalm-suppress InvalidArgument
         * @see https://github.com/vimeo/psalm/issues/6562
         */
        $maybeB = $apply->apply($f, $this);

        return $maybeB;
    }

    /**
     * @template B
     * @param Applicative $applicative
     * @param B $a
     * @return Maybe<B>
     */
    public static function pure(Applicative $applicative, $a): Maybe
    {
        /** @var Maybe<B> $maybeB */
        $maybeB = $applicative->pure($a);

        return $maybeB;
    }
}
