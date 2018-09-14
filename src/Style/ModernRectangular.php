<?php
    namespace Khalyomede\Style;

    use Khalyomede\ConsoleStylable;

    class ModernRectangular implements ConsoleStylable {
        public static function progressingCharacter(): string {
            return '◼';
        }

        public static function progressedCharacter(): string {
            return '◻';
        }

        public static function startCharacter(): string {
            return '[ ';
        }

        public static function endCharacter(): string {
            return '  ]';
        }
    }
?>