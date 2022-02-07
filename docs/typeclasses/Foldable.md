# Foldable

The `Foldable` typeclass provides the ability of collapsing a data structure which can be collapsed into a single value.

## API

The `Foldable` typeclass exposes a single method which allows collapsing a data structure into a single value, given an
initial value and a function to join two elements.

```php
interface Foldable
{
    /**
     * @param callable(A, B): B $f
     * @param B $b
     * @param T<A> $a
     * @return B
     */
    public function foldr(callable $f, $b, $a);
}
```

Its simplified type is

```
foldr :: ((A, B) -> B, B, T<A>) -> B
```
