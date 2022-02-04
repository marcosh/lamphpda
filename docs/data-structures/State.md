# State

The `State` datatype model values which can interact with a given state, both reading and writing to it. It allows
treating stateful computations in a completely pure fashion.

## Constructors

The `State` data structure has a single constructor which requires a function from the state to a pair consisting of the
value and an updated version of the state.

```php
/**
 * @template S
 * @template A
 */
final class State
{
    /**
     * @template T
     * @template B
     * @param callable(T): Pair<T, B> $runState
     * @return State<T, B>
     */
    public static function state(callable $runState): self
}
```

Its simplified type is

```
state :: (T -> Pair<T, B>) -> State<T, B>
```

## Eliminators

The is only one eliminator for `State`, which just evaluates the inner action for a given state, returning a value and a
possibly updated version of the state.

```php
    /**
     * @param S $state
     * @return Pair<S, A>
     */
    public function runState($state): Pair
```

Its simplified type is

```
runState :: (State<S, A>, S) -> Pair<S, A>
```

## Interpretation as an effect

The `State` data structure is used to model stateful computations in a pure fashion. A value of type `State<S, A>`
represents a stateful computation interacting with a state of type `S` and returning a value of type `A`.
