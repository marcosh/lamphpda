# Semigroup

A `Semigroup` instance provides a way to combine two things of the same type to create another instance of the same
type.

## API

The `Semigroup` typeclass exposes a method `append`, which requires two elements of the same type `A` to produce another
element of type `A`.

```php
interface Semigroup
{
    /**
     * @param A $a
     * @param A $b
     * @return A
     *
     * @psalm-pure
     */
    public function append($a, $b);
}
```

It simplified type is

```
append :: (A, A) -> A
```

## Laws

The only law associated to the `Semigroup` typeclass states that it should be associative.

### Associativity

```php
$semigroup->append($a, $semigroup->append($b, $c)) == $semigroup->append($semigroup->append($a, $b), $c)
```
