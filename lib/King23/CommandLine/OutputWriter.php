<?php
/*
 MIT License
 Copyright (c) 2010 - 2013 Peter Petermann

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
    const TYPE_Regular = 0;
    const TYPE_Heading = 1;
    const TYPE_Warning = 2;
    const TYPE_Error = 3;
    const TYPE_Positive = 4;

    const FONT_Bold = "\033[1m";
    const FONT_Underline = "\033[4m";
    const FONT_Blink = "\033[5m";
    const FONT_Invert = "\033[7m";

    const COLOR_FG_Black ="\033[0;30m30";
    const COLOR_FG_Red = "\033[0;31m";
    const COLOR_FG_Green = "\033[0;32m";
    const COLOR_FG_Yellow = "\033[0;33m";
    const COLOR_FG_Blue = "\033[0;34m";
    const COLOR_FG_Magenta ="\033[0;35m";
    const COLOR_FG_Cyan = "\033[0;36m";
    const COLOR_FG_LightGray = "\033[0;37m";
    const COLOR_FG_Default = "\033[0;39m";
    
    const COLOR_FGL_DarkGray ="\033[1;30m";
    const COLOR_FGL_Red ="\033[1;31m";
    const COLOR_FGL_Green = "\033[1;32m";
    const COLOR_FGL_Yellow = "\033[1;33m";
    const COLOR_FGL_Blue = "\033[1;34m";
    const COLOR_FGL_Magenta = "\033[1;35m";
    const COLOR_FGL_Cyan = "\033[1;36m";
    const COLOR_FGL_White = "\033[1;37m";

    const COLOR_BG_Black = "\033[40m";
    const COLOR_BG_Red = "\033[41m";
    const COLOR_BG_Green ="\033[42m";
    const COLOR_BG_Yellow ="\033[43m";
    const COLOR_BG_Blue ="\033[44m";
    const COLOR_BG_Magenta ="\033[45m";
    const COLOR_BG_Cyan = "\033[46m";
    const COLOR_BG_LightGray ="\033[47m";
    const COLOR_BG_Default ="\033[49m";

    /**
     * write a string to output, with optional type
     * @param String $message
     * @param Integer $type
     */
    public static function write($message, $type = OutputWriter::TYPE_Regular)
    {
        switch($type)
        {
            case self::TYPE_Heading:
                $fg = self::COLOR_FG_Default . self::FONT_Bold;
                $bg = self::COLOR_BG_Default;
                break;
            case self::TYPE_Warning:
                $fg = self::COLOR_FG_Yellow;
                $bg = self::COLOR_BG_Default;
                break;
            case self::TYPE_Error:
                $fg = self::COLOR_FG_Red;
                $bg = self::COLOR_BG_Default;
                break;
            case self::TYPE_Positive:
                $fg = self::COLOR_FG_Green;
                $bg = self::COLOR_BG_Default;
                break;
            case self::TYPE_Regular:
            default:
                $fg = self::COLOR_FG_Default;
                $bg = self::COLOR_BG_Default;
        }
        $message = self::COLOR_FG_LightGray . "[" . date("Y-m-d H:i:s") ."] " .$bg . $fg . $message . self::COLOR_FG_Default . self::COLOR_BG_Default . "\n";
        echo $message;
    }
 }
