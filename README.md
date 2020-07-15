# lamphpda

A collection of type-safe functional data structures

## Aim

The aim of this library is to provide a collection of functional data
structures in the most type safe way currently possible within the PHP
ecosystem.

## Main ideas

The two ideas which differentiate this from other functional libraries in PHP
are:

- a safe usage of sum types following [http://marcosh.github.io/post/2017/10/27/maybe-in-php-2.html](http://marcosh.github.io/post/2017/10/27/maybe-in-php-2.html)
- the usage of higher kinded types to increase reusability and type safety
    (see [http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-issue.html](http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-issue.html)
    and [http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-solution.html](http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-solution.html))

## Tools

We use [Psalm](https://psalm.dev/) as a type checker. It basically works as a
compilation step, ensuring that all the types are aligned.

To benefit from this library, it is compulsory that your code runs through a
Psalm check.

Run `./vendor/bin/grumphp run` to run the QA tasks, including the tests.