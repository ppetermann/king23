<?php
/*
 MIT License
 Copyright (c) 2010 - 2015 Peter Petermann

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
namespace King23\CommandLine\Theme;

use King23\CommandLine\Colors;
use King23\CommandLine\OutputWriter;

/**
 * Class K23, a color theme for the king23 CLI
 * @package King23\CommandLine\Theme
 */
class K23 implements Theme {

    /**
     * @param int $type
     * @return array
     */
    public function getColorsFor($type)
    {
        switch ($type) {
            case OutputWriter::TYPE_HEADING:
                $fg = Colors::COLOR_FG_DEFAULT . Colors::FONT_BOLD;
                $bg = Colors::COLOR_BG_DEFAULT;
                break;
            case OutputWriter::TYPE_WARNING:
                $fg = Colors::COLOR_FG_YELLOW;
                $bg = Colors::COLOR_BG_DEFAULT;
                break;
            case OutputWriter::TYPE_ERROR:
                $fg = Colors::COLOR_FG_RED;
                $bg = Colors::COLOR_BG_DEFAULT;
                break;
            case OutputWriter::TYPE_POSITIVE:
                $fg = Colors::COLOR_FG_GREEN;
                $bg = Colors::COLOR_BG_DEFAULT;
                break;
            default:
                $fg = Colors::COLOR_FG_DEFAULT;
                $bg = Colors::COLOR_BG_DEFAULT;
        }
        return array("fg" => $fg, "bg" => $bg);
    }
}