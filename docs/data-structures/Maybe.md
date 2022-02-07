# Maybe

The `Maybe<A>` data structure models the possibility of absence of values of type `A`.

It basically has the same semantic of a nullable type, with the benefit of being an object with a lot of behaviours
attached. This improves a lot composability and removes the need of null checking at every step.

## Constructors

The `Maybe` data structure has two constructors, one to build a structure which actually contains a value and one to
construct and empty structure.

```php
/**
 * @template A
 */
final class Maybe
{
    /**
     * @template B
     * @return Maybe<B>
     */
    public static function nothing(): self

    /**
     * @template B
     * @param B $value
     * @return Maybe<B>
     */
    public static function just($value): self
}
```

Their simplified type is

```
nothing :: () -> Maybe<B>
just :: B -> Maybe<B>
```

## Eliminators

The `Maybe` datatype has a single eliminator, which requires a value in the case it didn't have a value and a function
to be applied to the value, if it actually was there.

```php
    /**
     * @template B
     * @param B $ifNothing
     * @param callable(A): B $ifJust
     * @return B
     */
    public function eval($ifNothing, callable $ifJust)
```

It simplified type is

```
eval :: (Maybe<A>, B, (A -> B)) -> B
```

## Interpretation as an effect

The `Maybe` datatype can be interpreted as a model to denote the possibility of the absence of a value.

Its instances allow composing `Maybe` values as if the values were always there, managing the possibility of absence
under the hood.

## Typeclass instances

- `MaybeApplicative`
- `MaybeApply`
- `MaybeFoldable`
- `MaybeFunctor`
- `MaybeMonad`
- `MaybeMonadThrow`
- `MaybeTraversable`
