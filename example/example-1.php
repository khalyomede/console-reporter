<?php
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
?>