# Reader

The `Reader` data structure allows modelling values which depend on an environment. `Reader` turns out very useful for
implementing computations which depend on some external configuration. It could even be used as a system for dependency
injection.

## Constructors

The `Reader` data structure has a single constructor, which requires a function with the environment as a parameter.

```php
/**
 * @template E
 * @template A
 */
final class Reader
{
    /**
     * @template F
     * @template B
     * @param callable(F): B $f
     * @return Reader<F, B>
     */
    public static function reader(callable $f)
}
```

Its simplified type is

```
reader :: (F -> B) -> Reader<F, B>
```

## Evaluators

The only evaluator for `Reader` requires an instance of the environment and computes the action for that particular
environment.

```php
    /**
     * @param E $environment
     * @return A
     */
    public function runReader($environment)
```

Its simplified type is

```
runReader :: (Reader<E, A>, E) -> A
```

## Interpretation as an effect

The `Reader` datatype allows modelling values and computation which depend on an environment.
