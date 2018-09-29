<?php
    namespace Khalyomede;

    use InvalidArgumentException;
    use DateTime;
    use Khalyomede\Style\DefaultStyle;
    use Khalyomede\Severity;
    use Localgod\Console\Color;

    class ConsoleReporter {
        /**
         * The style class. 
         * This is a 
         * string, but 
         * used as a 
         * static class.
         * 
         * @var string
         */
        protected $style;

        /**
         * Current progression.
         * 
         * @var int
         */
        protected $currentIndex;

        /**
         * True if we should not print the progress at the end, else false.
         * 
         * @var bool
         */
        protected $clearProgress;

        /**
         * Holds the logs to be displayed.
         * 
         * @var array<string>
         */
        protected $logs;

        public function __construct() {
            $this->currentIndex = 1;
            $this->style = DefaultStyle::class;
            $this->clearProgress = true;
            $this->logs = [];
        }

        /**
         * Sets the maximum of entries to be displayed.
         * 
         * @throws InvalidArgumentException If the maximum is lower than 1.
         */
        public function setMaxEntries(int $maximum): ConsoleReporter {
            if( $maximum < 1 ) {
                throw new InvalidArgumentException('ConsoleReporter::setMaxEntries expects parameter 1 to be greater than 0');
            }
            
            $this->maximumEntries = $maximum;

            return $this;
        }   

        /**
         * Set the style, to customize the progress characters.
         * 
         * @param string The name of the style class that implements Khalyomede\Style\ConsoleStylable
         * @return Khalyomede\ConsoleReporter
         */
        public function setStyle(string $class): ConsoleReporter {
            $this->style = $class;

            return $this;
        }

        /**
         * Moves the progression.
         * 
         * @throws InvalidArgumentException If the increment is lower than 1.
         */
        public function advance(?int $increment = null): ConsoleReporter {
            if( is_null($increment) === false && $increment < 1 ) {
                throw new InvalidArgumentException('ConsoleReporter::advance expects parameter 1 to be greater than 0');
            }
            
            $inc = $increment;

            if( is_null($increment) ) {
                $inc = 1;
            }
            
            $this->currentIndex += $inc;
            
            return $this;
        }

        public function doNotClearProgress(): ConsoleReporter {
            $this->clearProgress = false;

            return $this;
        }

        public function info(string $message): ConsoleReporter {
            $this->printLog($message, $this->now(), Severity::INFO);

            return $this;
        }

        private function now(): string {
            return DateTime::createFromFormat('U.u', microtime(TRUE))->format('Y-m-d H:i:s.u');
        }

        private function printLog($message, $time, $severity): void {
            echo str_pad("  {$time} [{$severity}] {$message}", $this->maxProgressBarLength(), ' ', STR_PAD_RIGHT) . "\n";
        }

        public function warning(string $message): ConsoleReporter {
            $this->printLog($message, $this->now(), Severity::WARNING);

            return $this;
        }

        public function debug(string $message): ConsoleReporter {
            $this->printLog($message, $this->now(), Severity::DEBUG);

            return $this;
        }

        public function error(string $message): ConsoleReporter {
            $this->printLog($message, $this->now(), Severity::ERROR);

            return $this;
        }

        /**
         * If this is the first report, print a new line to improve the readability.
         */
        private function printLineReturnInTheBegining(): ConsoleReporter {
            if( $this->currentIndex === 1 ) {
                echo "\n";
            }

            return $this;
        }

        /**
         * If this is the last report, print a new line to improve the readability.
         */
        private function printLineReturnInTheEnd(): ConsoleReporter {
            if( $this->currentIndex === $this->maximumEntries ) {
                echo "\n";
            }

            return $this;
        }

        /**
         * Prints the reporting in the console.
         * 
         * @return void
         */
        public function report(): ConsoleReporter {
            $this->printProgress();

            if( $this->currentIndex === $this->maximumEntries ) {
                if( $this->clearProgress === false ) {
                    $this->printProgress($clear = false);
                }
            }

            return $this;
        }

        /**
         * Prints the current progression.
         * 
         * @return void
         */
        private function printProgress(bool $clear = true): void {
            $current = str_pad($this->currentIndex, strlen($this->maximumEntries), "0", STR_PAD_LEFT);
            $percentage = str_pad(round(($this->currentIndex / $this->maximumEntries) * 100, 0, PHP_ROUND_HALF_DOWN), 3, '0', STR_PAD_LEFT);

            echo "  $current / {$this->maximumEntries} ";
            echo $this->style::startCharacter();
            echo str_repeat($this->style::progressedCharacter(), $this->currentIndex) . str_repeat($this->style::progressingCharacter(), $this->maximumEntries - $this->currentIndex);
            echo $this->style::endCharacter();
            echo " $percentage %\r";
        }

        /**
         * Returns the max length of the progress bar.
         */
        private function maxProgressBarLength(): int {
            return 2 + strlen($this->maximumEntries) + 3 + strlen($this->maximumEntries) + strlen($this->style::startCharacter()) + $this->maximumEntries + strlen($this->style::endCharacter()) + 7;
        }
    }
?>