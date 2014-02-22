<?php
/*
 MIT License
 Copyright (c) 2010 - 2014 Peter Petermann

 Permission is hereby granted, free of charge, to any person
 obtaining a copy of this software and associated documentation
 files (the "Software"), to deal in the Software without
 restriction, including without limitation the rights to use,
 copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the
 Software is furnished to do so, subject to the following
 conditions:

 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 OTHER DEALINGS IN THE SOFTWARE.

*/
namespace King23\CommandLine;
use King23\CommandLine\Theme\K23;
use King23\CommandLine\Theme\Theme;

/**
 * class simplifying the use of color codes on console
 */
class OutputWriter
{
    const TYPE_REGULAR = 0;
    const TYPE_HEADING = 1;
    const TYPE_WARNING = 2;
    const TYPE_ERROR = 3;
    const TYPE_POSITIVE = 4;

    /**
     * @var Theme
     */
    public static $theme;


    /**
     * get theme to be used
     * @return Theme
     */
    public static function getTheme()
    {
        if (!isset(self::$theme)) {
            self::$theme = new K23();
        }
        return self::$theme;
    }


    /**
     * write a string to output, with optional type
     *
     * @param String $message
     * @param Integer $type
     */
    public static function write($message, $type = OutputWriter::TYPE_REGULAR)
    {
        $colors = self::getTheme()->getColorsFor($type);

        // timestamp will always be lightgray no matter the message type
        $messageout = Colors::COLOR_FG_LIGHTGRAY . "[".date("Y-m-d H:i:s")."] ";

        // set to type colors
        $messageout .= $colors['bg'] . $colors['fg'];

        // add message
        $messageout .= $message;

        // reset to default colors
        $messageout .= Colors::COLOR_FG_DEFAULT . Colors::COLOR_BG_DEFAULT;

        echo $messageout;
    }

    /**
     * write a line to output with optional type
     * @param String $message
     * @param int $type
     */
    public static function writeln($message, $type = OutputWriter::TYPE_REGULAR)
    {
        self::write($message, $type);
        echo PHP_EOL;
    }
}
