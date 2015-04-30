# PSR-3 Log message assertions

[![Build Status](https://travis-ci.org/Maks3w/Psr3MessagesAssertions.svg?branch=master)](https://travis-ci.org/Maks3w/Psr3MessagesAssertions)
[![Coverage Status](https://coveralls.io/repos/Maks3w/Psr3MessagesAssertions/badge.svg?branch=master)](https://coveralls.io/r/Maks3w/Psr3MessagesAssertions?branch=master)

Test your log messages are compliant with the [PSR-3 (Logger) specification](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md)

## Installing via Composer

You can use [Composer](https://getcomposer.org) .

```bash
composer require fr3d/psr3-message-assertions
```

## Usage in PHPUnit

Use [TestLogger.php](src/PhpUnit/TestLogger.php) as the logger dependency.

`TestLogger` will assert each log message is compliant with the PSR-3 Logger standard.

## License

  Code licensed under BSD 2 clauses terms & conditions.

  See [LICENSE.txt](LICENSE.txt) for more information.
