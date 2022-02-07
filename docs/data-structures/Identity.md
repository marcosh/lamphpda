# Identity

The identity data structure is quite a trivial one, working just as a wrapper around a given type.

## Constructors

The only constructor of `Identity` is `wrap`, which just wraps a value

```php
/**
 * @template A
 */
final class Identity
{
    /**
     * @template B
     * @param B $b
     * @return IO<B>
     */
    public static function wrap($b): self
}
```

Synthetically, its type is

```
wrap :: B -> Identity<B>
```

## Eliminators

The only eliminator for `Identity` is `unwrap` which just removes the `Identity` layer

```php
    /**
     * @return A
     */
    public function unwrap()
```

Its simplified type is

```
unwrap :: Identity A -> A
```

## Interpretation as an effect

The `Identity` datatype actually describes the absence of effects.

## Typeclass instances

- `IdentityApplicative`
- `IdentityApply`
- `IdentityFoldable`
- `IdentityFunctor`
- `IdentityMonad`
- `IdentityTraversable`
