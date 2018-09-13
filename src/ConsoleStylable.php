<?php
    namespace Khalyomede;

    interface ConsoleStylable {
        /**
         * Returns the character to display in the 
         * progress bar to indicate a progression. 
         * This should be a white background character 
         * to give a better visual effect.
         */
        public static function progressingCharacter(): string;

        /**
         * Returns the character to display in the 
         * progress bar to indicate a end of 
         * progression. This should be a white 
         * transparent or faded character to give 
         * a better visual effect.
         */
        public static function progressedCharacter(): string;

        /**
         * Returns the character to place at the 
         * begining of the progress bar.
         */
        public static function startCharacter(): string;

        /**
         * Returns the character to place at the end 
         * of the progress bar.
         */
        public static function endCharacter(): string;
    }
?>