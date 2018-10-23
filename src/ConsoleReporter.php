<?php
    namespace Khalyomede;

    use DateTime;
    use Khalyomede\Style\DefaultStyle;
    use Khalyomede\Severity;
    use Localgod\Console\Color;
    use RuntimeException;
    use InvalidArgumentException;

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

        /**
         * Current severity (used for the log).
         * 
         * @var string
         */
        protected $severity;

        /**
         * Stores the maximum entries to compute the percentage and display the progress bar "progressing" characters.
         * 
         * @var int
         */
        protected $maximumEntries;

        /**
         * Tells the reporter to display icons for the severity instead of text.
         * 
         * @var bool
         */
        protected $displaySeverityWithIcons;

        /**
         * Tells the reporter to display a restricted version of the progress bar.
         * 
         * @var bool
         */
        protected $restrictedProgressBarSize;

        /**
         * Sets the max lenght of the progress bar if needed.
         * 
         * @var int
         * @see ConsoleReporter::$restrictedProgressBarSize
         * @see ConsoleReporter::setProgressBarSize()
         */
        protected $maxProgressBarSize;

        public function __construct() {
            $this->currentIndex = 1;
            $this->style = DefaultStyle::class;
            $this->clearProgress = true;
            $this->logs = [];
            $this->restrictedProgressBarSize = false;
            $this->maxProgressBarSize = 0;
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
         * @param string $class The name of the style class that implements Khalyomede\Style\ConsoleStylable
         * @return \Khalyomede\ConsoleReporter
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
            $this->severity = Severity::INFO;
            $this->printLog($message, $this->now());

            return $this;
        }

        public function success(string $message): ConsoleReporter {
            $this->severity = Severity::SUCCESS;
            $this->printLog($message, $this->now());

            return $this;
        }

        private function now(): string {
            $datetime = DateTime::createFromFormat('U.u', (string) microtime(TRUE));
            
            return $datetime->format('Y-m-d H:i:s.u');
        }

        private function printLog($message, $time): void {
            $severity = $this->displaySeverityWithIcons === true ? $this->severityToIcon() : $this->severityToText();

            echo str_pad("  {$time} $severity {$message}", $this->maxProgressBarLength(), ' ', STR_PAD_RIGHT) . "\n";
        }

        /**
         * Get the severity as text.
         * 
         * @return string
         */
        private function severityToText(): string {
            return "[{$this->severity}]";
        }

        /**
         * Get the severity as icon.
         * 
         * @return string
         */
        private function severityToIcon(): string {
            $icon = '';

            switch( $this->severity ) {
                case Severity::INFO:
                    $icon = ' ⓘ ';

                    break;

                case Severity::DEBUG:
                    $icon = ' ⚐ ';

                    break;

                case Severity::WARNING:
                    $icon = ' ⚠ ';

                    break;

                case Severity::ERROR:
                    $icon = ' ✖ ';

                    break;

                case Severity::SUCCESS:
                    $icon = ' ✓ ';

                    break;
                
                default:
                    throw new RuntimeException('Unsupported severity "' . $this->severity . '"');

                    break;
            }

            return $icon;
        }

        public function warning(string $message): ConsoleReporter {
            $this->severity = Severity::WARNING;
            $this->printLog($message, $this->now());

            return $this;
        }

        public function debug(string $message): ConsoleReporter {
            $this->severity = Severity::DEBUG;
            $this->printLog($message, $this->now());

            return $this;
        }

        public function error(string $message): ConsoleReporter {
            $this->severity = Severity::ERROR;
            $this->printLog($message, $this->now());

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
            echo str_repeat($this->style::progressedCharacter(), $this->numberOfProgressCharacterToPrint()) . str_repeat($this->style::progressingCharacter(), ($this->maximumEntries()) - $this->numberOfProgressCharacterToPrint());
            echo $this->style::endCharacter();
            echo " $percentage %\r";
        }

        /**
         * Returns the number of progressed character to displayed.
         * This depends whether we are in restricted progress bar size mode or not, using ConsoleReporter::setProgressBarSize().
         * 
         * @return int
         */
        private function maximumEntries(): int {
            return $this->restrictedProgressBarSize === true ? $this->maxProgressBarSize : $this->maximumEntries;
        }

        /**
         * Returns the max length of the progress bar.
         */
        private function maxProgressBarLength(): int {
            return 2 + strlen($this->maximumEntries) + 3 + strlen($this->maximumEntries) + strlen($this->style::startCharacter()) + ($this->maximumEntries()) + strlen($this->style::endCharacter()) + 7;
        }

        /**
         * Set icons on the logs instead of text for the severity.
         * 
         * @return \Khalyomede\ConsoleReporter
         * @example 
         * $reporter = new \Khalyomede\ConsoleReporter;
         * $reporter->displaySeverityWithIcons();
         */
        public function displaySeverityWithIcons(): ConsoleReporter {
            $this->displaySeverityWithIcons = true;
            
            return $this;
        }

        /**
         * Force the progress bar do adopt a fixed size.
         * 
         * @param int $size The desired size.
         * @return \Khalyomede\ConsoleReporter
         * @throws InvalidArgumentException If the size is lower than 1.
         */
        public function setProgressBarSize(int $size): ConsoleReporter {
            if( $size < 1 ) {
                throw new InvalidArgumentException('ConsoleReporter::setProgressBarSize expects parameter 1 to be an integer greater than 0');
            }

            $this->restrictedProgressBarSize = true;
            $this->maxProgressBarSize = $size;
            
            return $this;
        }

        /**
         * Returns the number of "progress character" to print.
         * This depends on wether the restricted progress bar 
         * size have been enabled with 
         * ConsoleReporter::setProgressBarSize() or not.
         * 
         * @return int
         */
        private function numberOfProgressCharacterToPrint(): int {
            $numberOfProgressCharacterToPrint = $this->currentIndex;

            if( $this->restrictedProgressBarSize === true ) {
                $numberOfProgressCharacterToPrint = (int) floor( ($this->currentIndex * $this->maxProgressBarSize) / $this->maximumEntries );
            }

            return $numberOfProgressCharacterToPrint;
        }
    }
?>