# Methods come from typeclasses

## Context

Functional programming data structures, as the ones implemented in this library, offer the possibility of defining a 
great number of combinators. Almost all of them are specializations of operations coming from typeclasses.

## Decision

To keep the API small and not opinionated, we decide to provide as much as possible only operations coming from
typeclasses.

## Consequences

This means that the API will be kept minimal to expose only methods coming from implemented typeclasses.
Moreover, whenever a new combinator needs to be introduced, first we are going to define a typeclass which provides it.
