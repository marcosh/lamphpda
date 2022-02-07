# MonadThrow

The `MonadThrow` typeclass provides the ability to exit a computation with an error value.

## Parent

The `MonadThrow` typeclass extends the `Monad` typeclass.

## API

The `MonadThrow` typeclass add a single method to the `Monad` interface, providing the ability of exiting a computation
with an error value.

```php
interface MonadThrow extends Monad
{
    /**
     * @param E $e
     * @return F<A>
     */
    public function throwError($e);
}
```

Its simplified signature is

```
throwError :: E -> F<A>
```

## Laws

The `MonadThrow` typeclass should satisfy one addition laws with respect to the ones required by the `Functor`, `Apply`,
`Applicative` and `Monad` typeclasses.

### Left zero

```php
$monadThrow->bind($monadThrow->throwError($e), $f) == $monadThrow->throwError($e)
```
