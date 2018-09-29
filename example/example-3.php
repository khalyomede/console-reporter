<?php
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
?>