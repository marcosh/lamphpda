# How to write annotations

## Context

With the abundant usage of [Psalm](https://psalm.dev/), this library makes a heavy usage of annotations. It makes sense
to have a standardized way to write them, so that new contributors could conform to it.

## Decision

Class level annotations should be written as

```php
/**
 * @template A of B
 * @implements
 *
 * @psalm-immutable
 */
```

Method and function annotations should be written as

```php
/**
 * @template
 * @param
 * @return
 *
 * @psalm-pure/@psalm-mutation-free
 *
 * @psalm-suppress
 */
```

What matters is the order and the spacing.
Not every type of annotaion should be present for every class or method.

## Consequences

Everyone can know how to write annotations.
