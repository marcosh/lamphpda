# Pair

The `Pair` data structure is a generic way to keep track of two independent values at the same time in a type safe way.

## Constructors

The `Pair` datatype has a single constructor which requires the two values of the pair.

```php
/**
 * @template A
 * @template B
 */
final class Pair
{
    /**
     * @template C
     * @template D
     * @param C $c
     * @param D $d
     * @return Pair<C, D>
     */
    public static function pair($c, $d): self
}
```

Its simplified type is

```
pair :: (C, D) -> Pair<C, D>
```

## Evaluators

There is a single evaluator for `Pair`, which requires a function which consumes both elements of the pair.

```php
    /**
     * @template C
     * @param callable(A, B): C $f
     * @return C
     */
    public function eval(callable $f)
```

It simplified type is

```
eval :: (Pair<A, B>, ((A, B) -> C)) -> C
```

## Interpretation as an effect

The `Pair` data structure is also known as `Writer` in the literature. A `Pair` could be seen as a context where we
attached additional information to a value; this information could be metadata or logging details.

Pay attention that all the instances, except the `Bifunctor` one, work exclusively on the second component of the pair.
For example, if you map a pair, only the second component will be affected.

## Typeclass instances

- `PairApplicative`
- `PairApply`
- `PairBifunctor`
- `PairFunctor`
- `PairMonad`
