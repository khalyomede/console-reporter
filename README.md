# Console reporter


![PHP from Packagist](https://img.shields.io/packagist/php-v/khalyomede/console-reporter.svg)
![Packagist](https://img.shields.io/packagist/v/khalyomede/console-reporter.svg)
![Packagist](https://img.shields.io/packagist/l/khalyomede/console-reporter.svg)

```php
$numbers = range(1, 10);
$reporter->setMaxEntries(10);

foreach( $numbers as $number ) {
    $reporter->report();
    $reporter->advance();
}
```

```bash
10 / 10 ░░░░░░░░░ 100 %
```

## Summary

- [Installation](#installation)
- [Example of use](#example-of-use)

## Installation

```bash
composer require --dev khalyomede/console-reporter:0.*
```

## Example of use

- [Example 1: simple use case](#example-1-simple-use-case)

### Example 1: simple use case

```php
require(__DIR__ . '/../vendor/autoload.php');

use Khalyomede\ConsoleReporter as Reporter;

$reporter = new Reporter;
$reporter->setMaxEntries(48);
$reporter->doNotClearProgress();
$reporter->info('No errors, 48 tests completed.');

foreach( range(1, 48) as $integer ) {
  usleep(100000);

  $reporter->report();
  $reporter->advance();
}
```

```bash
$ php example/example-1.php

  48 / 48 ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ 100 %

  2018-09-13 21:42:19.453300 [INFO] No errors, 48 tests completed.
```