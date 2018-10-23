# Console reporter


![PHP from Packagist](https://img.shields.io/packagist/php-v/khalyomede/console-reporter.svg)
![Packagist](https://img.shields.io/packagist/v/khalyomede/console-reporter.svg)
![Packagist](https://img.shields.io/packagist/l/khalyomede/console-reporter.svg)

![Gif showing a command line that trigger the console report, with the progress bar and the logs that stack themselves above the progress bar as the progress bar advance until 100 percent](https://image.ibb.co/n63Fuz/landing_gif_2_khalyomede_console_reporter.gif)

## Summary

- [Installation](#installation)
- [Example of use](#example-of-use)
- [Available built-in progress bar styles](#available-built-in-progress-bar-styles)
- [Supported shells](#supported-shells)

## Installation

```bash
composer require --dev khalyomede/console-reporter:0.*
```

## Example of use

- [Example 1: simple use case](#example-1-simple-use-case)
- [Example 2: using a built-in progress bar style](#example-2-using-a-built-in-progress-bar-style)
- [Example 3: using your own progress bar style](#example-3-using-your-own-progress-bar-style)
- [Example 4: display logs using icons](#example-4-display-logs-using-icons)
- [Example 5: force the size of the progress bar](#example-5-force-the-size-of-the-progress-bar)

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

### Example 2: using a built-in progress bar style

```php
require(__DIR__ . '/../vendor/autoload.php');

use Khalyomede\ConsoleReporter as Reporter;
use Khalyomede\Style\ModernRounded;

$reporter = new Reporter;
$reporter->setMaxEntries(24);
$reporter->setStyle(ModernRounded::class);

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

```
$ php example/example-2.php
  2018-09-29 18:07:40.433600 [INFO] test #8 in progression...
  2018-09-29 18:07:42.810300 [INFO] test #16 in progression...
  2018-09-29 18:07:45.291100 [INFO] test #24 in progression...
  24 / 24 [●●●●●●●●●●●●●●●●●●●●●●●●] 100 %
```

### Example 3: using your own progress bar style

```php
require(__DIR__ . '/../vendor/autoload.php');

use Khalyomede\ConsoleStylable;
use Khalyomede\ConsoleReporter as Reporter;

class ModernStar implements ConsoleStylable {
  public static function progressingCharacter(): string {
    return '✧';
  }

  public static function progressedCharacter(): string {
    return '✦';
  }

  public static function startCharacter(): string {
    return '[';
  }

  public static function endCharacter(): string {
    return ']';
  }
}

const MAX = 24;

$numbers = range(1, MAX);

$reporter = new Reporter;

$reporter->setMaxEntries(MAX);
$reporter->setStyle(ModernStar::class);

foreach( $numbers as $number ) {
  $microseconds = rand(50000, 500000);

  usleep($microseconds);

  if( $number % 8 === 0 ) {
    $reporter->info("running iteration #$number");
  }

  $reporter->report();
  $reporter->advance();
}
```

```bash
$ php example/example-3.php
  2018-09-29 20:34:16.945300 [INFO] running iteration #8
  2018-09-29 20:34:18.934700 [INFO] running iteration #16
  2018-09-29 20:34:21.071200 [INFO] running iteration #24
  24 / 24 [✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦✦] 100 %
```

### Example 4: display logs using icons

```php
require(__DIR__ . '/../vendor/autoload.php');

use Khalyomede\ConsoleReporter as Reporter;

$reporter = new Reporter;
$reporter->setMaxEntries(24);
$reporter->displaySeverityWithIcons();

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
$ php example/example-4.php
  2018-10-04 21:42:31.842600  ⓘ  test #8 in progression...
  2018-10-04 21:42:33.992700  ⓘ  test #16 in progression...
  2018-10-04 21:42:35.393700  ⓘ  test #24 in progression...
  24 / 24 ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ 100 %
```

### Example 5: force the size of the progress bar

```php
require(__DIR__ . '/../vendor/autoload.php');

use Khalyomede\ConsoleReporter as Reporter;

$reporter = new Reporter;
$reporter->setMaxEntries(10);
$reporter->setProgressBarSize(30);

$numbers = range(0, 9);

foreach( $numbers as $number ) {
  $sleepTimeInMicroSeconds = rand(50000, 500000);
  
  usleep($sleepTimeInMicroSeconds);

  $reporter->report();
  $reporter->advance();
}
```

```bash
$ php example/example-5.php
  10 / 10 ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ 100 %
```

## Available built-in progress bar styles

- [Modern rounded](#modern-rounded)
- [Modern rectangular](#modern-rectangular)

### Modern rounded

```bash
2018-09-30 14:52:31.158200 [INFO] test #8 in progression...
2018-09-30 14:52:32.366100 [INFO] test #16 in progression...
2018-09-30 14:52:33.885100 [INFO] test #24 in progression...
24 / 24 [●●●●●●●●●●●●●●●●●●●●●●●●] 100 %
```

### Modern rectangular

```bash
2018-09-30 14:55:05.430200 [INFO] test #8 in progression...
2018-09-30 14:55:07.795900 [INFO] test #16 in progression...
2018-09-30 14:55:09.755300 [INFO] test #24 in progression...
24 / 24 [◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼◼] 100 %
```

## Supported shells

| Operating system | Shell      | Tested ? | Working ? | Need configuration ? |
|------------------|------------|----------|-----------|----------------------|
| Windows          | bash       | ✔        | ✔         |                      |
| Windows          | MS-DOS     | ✔        | ✔         | yes                  |
| Windows          | PowerShell | ✔        | ✔         | yes                  |

Do no see your OS or shell ? This means it has not been tested. 

You are more than welcomed if you want to test it in another configuration than one of the above. Just fill in an issue.

### PowerShell configuration

Check that your font configuration is **not** set to `Raster Fonts`:

![Photo of the powersheel configuration panel, on the font section](https://image.ibb.co/b79c7K/Power_Shell_Properties_Font_size_thumb.png)

Check it to something else than `Raster Fonts`. The characters used in some progress bar should now apear correctly.

## Credits

Powershell font photo taken from [4sysops](https://4sysops.com/archives/change-powershell-console-font-size-with-cmdlet/)