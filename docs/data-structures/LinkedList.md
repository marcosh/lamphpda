# LinkedList

A [linked list](https://www.wikiwand.com/en/Linked_list) is a collection of homogeneous elements where we always keep a
reference to the first element of the list and to the rest of the list.

## Constructors

The `LinkedList` data structure has two constructors, one to build an empty list and one to build a non-empty list from
an element and another `LinkedList`.

```php
/**
 * @template A
 */
final class LinkedList
{
    /**
     * @template B
     * @return LinkedList<B>
     */
    public static function empty(): self

    /**
     * @template B
     * @param B $head
     * @param LinkedList<B> $tail
     * @return LinkedList<B>
     */
    public static function cons($head, self $tail): self
}
```

Their simplified type is

```
empty :: () -> LinkedList<B>
cons :: (B, LinkedList<B>) -> LinkedList<B>
```

## Eliminators

There is only one eliminator for a `LinkedList`, which recursively traverses all the elements of the list. It does so
requiring an initial value and a function to combine every element with the result computed until then.

```php
    /**
     * @template B
     * @param callable(A, B): B $op
     * @param B $unit
     * @return B
     */
    public function eval(callable $op, $unit)
```

Its simplified type is

```
eval :: (LinkedList<A>, (A, B) -> B, B) -> B
```

## Interpretation as an effect

The usual interpretation of a list as an effect is non-determinism. You can convince yourself that this makes sense
looking at a list as the possible values of a probabilistic variable.

This interpretation is consistent with the behaviour of the `Apply` and the `Monad` instance for a `LinkedList`. For
example, the `Apply` instance allows to apply a `LinkedList` of functions to a `LinkedList` of values; the result is the
`LinkedList` containing all the results of applying every function to every value.
