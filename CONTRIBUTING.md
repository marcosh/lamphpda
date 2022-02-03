# Contributing

- we format the code using [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) according to the configuration in [.php-cs-fixer.dist.php](.php-cs-fixer.dist.php);
- we analise the code statically to check the typing using [Psalm](https://psalm.dev/) according to the configuration in [psalm.xml](psalm.xml);
- we test the code using [Kahlan](https://github.com/kahlan/kahlan);
- we use [composerRequireChecker](https://github.com/maglnet/ComposerRequireChecker) to avoid transitive dependencies.

In the `bin` folder there are some scripts which provide containerized versions of the executables needed by the project.

Before proposing some change, be sure to read the architectural decisions in the [`adr`](adr) folder.

Please ensure that any new feature is covered by unit tests and typed appropriately.
