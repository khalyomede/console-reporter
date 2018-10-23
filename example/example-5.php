<?php
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
?>