# Plus

The `Plus` typeclass provides monoid-like operations on type constructors instead of values.

## Parent

The `Plus` typeclass extends the `Alt` typeclass.

## API

The `Plus` typeclass provides a single method, which returns the identity element of the `alt` operation defined by the `Alt` instance.

```php
interface Plus extends Alt
{
    /**
     * @return F<A>
     */
    public function empty();
}
```

Its simplified type is

```
empty :: () -> F<A>
```

## Laws

The `Plus` typeclass requires several laws to hold, in addition to the ones required by the `Alt` typeclass.

### Left identity

```php
$alternative->alt($alternative->empty(), $x) == $x
```

### Right identity

```php
$alternative->alt($x, $alternative->empty()) == $x
```

```php
$alternative->map($f, $alternative->empty()) == $alternative->empty()
```

## Implemented instances

- `EitherPlus`