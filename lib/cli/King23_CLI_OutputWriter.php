<?php
class King23_CLI_OutputWriter
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

    public static function write($message, $type = King23_CLI_OutputWriter::TYPE_Regular)
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
        $message = self::COLOR_FGL_DarkGray . "[" . date("Y-m-d H:i:s") ."] " .$bg . $fg . $message . self::COLOR_FG_Default . self::COLOR_BG_Default . "\n";
        echo $message;
    }
 }