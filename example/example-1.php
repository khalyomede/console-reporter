<?php
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
?>