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

    const FONT_BOLD = "\033[1m";
    const FONT_UNDERLINE = "\033[4m";
    const FONT_BLINK = "\033[5m";
    const FONT_INVERT = "\033[7m";

    const COLOR_FG_BLACK = "\033[0;30m30";
    const COLOR_FG_RED = "\033[0;31m";
    const COLOR_FG_GREEN = "\033[0;32m";
    const COLOR_FG_YELLOW = "\033[0;33m";
    const COLOR_FG_BLUE = "\033[0;34m";
    const COLOR_FG_MAGENTA = "\033[0;35m";
    const COLOR_FG_CYAN = "\033[0;36m";
    const COLOR_FG_LIGHTGRAY = "\033[0;37m";
    const COLOR_FG_DEFAULT = "\033[0;39m";

    const COLOR_FGL_DARKGRAY = "\033[1;30m";
    const COLOR_FGL_RED = "\033[1;31m";
    const COLOR_FGL_GREEN = "\033[1;32m";
    const COLOR_FGL_YELLOW = "\033[1;33m";
    const COLOR_FGL_BLUE = "\033[1;34m";
    const COLOR_FGL_MAGENTA = "\033[1;35m";
    const COLOR_FGL_CYAN = "\033[1;36m";
    const COLOR_FGL_WHITE = "\033[1;37m";

    const COLOR_BG_BLACK = "\033[40m";
    const COLOR_BG_RED = "\033[41m";
    const COLOR_BG_GREEN = "\033[42m";
    const COLOR_BG_YELLOW = "\033[43m";
    const COLOR_BG_BLUE = "\033[44m";
    const COLOR_BG_MAGENTA = "\033[45m";
    const COLOR_BG_CYAN = "\033[46m";
    const COLOR_BG_LIGHTGRAY = "\033[47m";
    const COLOR_BG_DEFAULT = "\033[49m";

    /**
     * write a string to output, with optional type
     *
     * @param String $message
     * @param Integer $type
     */
    public static function write($message, $type = OutputWriter::TYPE_REGULAR)
    {
        switch ($type) {
            case self::TYPE_HEADING:
                $fg = self::COLOR_FG_DEFAULT.self::FONT_BOLD;
                $bg = self::COLOR_BG_DEFAULT;
                break;
            case self::TYPE_WARNING:
                $fg = self::COLOR_FG_YELLOW;
                $bg = self::COLOR_BG_DEFAULT;
                break;
            case self::TYPE_ERROR:
                $fg = self::COLOR_FG_RED;
                $bg = self::COLOR_BG_DEFAULT;
                break;
            case self::TYPE_POSITIVE:
                $fg = self::COLOR_FG_GREEN;
                $bg = self::COLOR_BG_DEFAULT;
                break;
            case self::TYPE_REGULAR:
            default:
                $fg = self::COLOR_FG_DEFAULT;
                $bg = self::COLOR_BG_DEFAULT;
        }
        $messageout = self::COLOR_FG_LIGHTGRAY;
        $messageout .= "[".date("Y-m-d H:i:s")."] ".$bg.$fg;
        $messageout .= $message.self::COLOR_FG_DEFAULT.self::COLOR_BG_DEFAULT."\n";
        echo $messageout;
    }
}
