# IO

The `IO<A>` datatype describes a computation which will return a value of type `A`.

## Constructors

The `IO<A>` data structure has only one constructor, called `action`, which requires a callable with no arguments and
return type `A`.

```php
/**
 * @template A
 */
class IO
{
    /**
     * @param callable(): A $action
     * @return IO<A>
     */
    public static function action(callable $action): self
}
```

Its simplified type is

```
action :: (() -> A) -> IO<A>
```

## Eliminators

The only eliminator of `IO` is `eval`, which just evaluates the action represented by `IO`.

```php
    /**
     * @return A
     */
    public function eval()
```

It simplified type is

```
eval :: IO<A> -> A
```

## Interpretation as an effect

Representing a generic computation, the `IO` datatype given access to any possible effect.

The `IO` data structure should be used whenever there is the need to interact with the external world, being it a
database, a user or a web api.
