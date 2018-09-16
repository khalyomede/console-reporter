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
$reporter->setMaxEntries(24);

foreach( range(1, 24) as $integer ) {
  $sleepTimeInMicroseconds = rand(5000, 500000);

  usleep($sleepTimeInMicroseconds);

  if( $integer % 8 === 0 ) {
    $reporter->info("test #$integer in progression...");
  }

  $reporter->report();
  $reporter->advance();
}
```

```bash
$ php example/example-1.php

  2018-09-16 08:45:29.595600 [INFO] test #8 in progression...

  2018-09-16 08:45:32.205500 [INFO] test #16 in progression...

  2018-09-16 08:45:34.570100 [INFO] test #24 in progression...

  24 / 24 ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ 100 %
```

## Supported shells

| Operating system | Shell      | Tested ? | Working ? | Need configuration ? |
|------------------|------------|----------|-----------|----------------------|
| Windows          | bash       | ✔        | ✔         |                      |
| Windows          | MS-DOS     | ✔        |           |                      |
| Windows          | PowerShell | ✔        | ✔         | yes                  |

Do no see your OS or shell ? This means it has not been tested. 

You are more than welcomed if you want to test it in another configuration than one of the above. Just fill in an issue.

## Credits

Powershell font photo taken from [4sysops](https://4sysops.com/archives/change-powershell-console-font-size-with-cmdlet/)