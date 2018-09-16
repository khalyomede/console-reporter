# Console reporter


![PHP from Packagist](https://img.shields.io/packagist/php-v/khalyomede/console-reporter.svg)
![Packagist](https://img.shields.io/packagist/v/khalyomede/console-reporter.svg)
![Packagist](https://img.shields.io/packagist/l/khalyomede/console-reporter.svg)

![Gif showing a command line that trigger the console report, with the progress bar and the logs that stack themselves above the progress bar as the progress bar advance until 100 percent](https://image.ibb.co/n63Fuz/landing_gif_2_khalyomede_console_reporter.gif)

## Summary

- [Installation](#installation)
- [Example of use](#example-of-use)
- [Supported shells](#supported-shells)

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

## Supported shells

| Operating system | Shell      | Tested ? | Working ? | Need configuration ? |
|------------------|------------|----------|-----------|----------------------|
| Windows          | bash       | ✔        | ✔         |                      |
| Windows          | MS-DOS     | ✔        |           |                      |
| Windows          | PowerShell | ✔        | ✔         | yes                  |

Do no see your OS or shell ? This means it has not been tested. 

You are more than welcomed if you want to test it in another configuration than one of the above. Just fill in an issue.