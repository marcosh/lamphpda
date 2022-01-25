# Purity of callables

## Context

To preserve the purity of some method we need to receive only pure callables. On the other hand currently purity annotations
on Psalm are too strict, not allowing sane functions.

## Decision

To allow users to provide all the callables which make sense, we allow to provide even unsafe callables. When Psalm will
restructure its management of purity, we will review this decision.

## Consequences

We suppress all the Psalm issues requesting purity of callables
