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
 * conveniance class for output in the King23 CLI
 */
final class CLI implements \King23\Core\Interfaces\Singleton
{
    private static $myInstance =null;

    private function  __construct()
    {
    }

    /**
     * @return \King23\CommandLine\CLI
     */
    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new CLI();
        return self::$myInstance;
    }

    /**
     * Write a string as regular message to output
     * @param String $message
     */
    public function message($message ="")
    {
        OutputWriter::write($message);
    }

    /**
     * Write a string colorcoded as error to the output
     * @param String $message
     */
    public function error($message)
    {
        OutputWriter::write($message, OutputWriter::TYPE_ERROR);
    }

    /**
     * Write a string colorcoded as warning to the output
     * @param String $message
     */
    public function warning($message)
    {
        OutputWriter::write($message, OutputWriter::TYPE_WARNING);
    }

    /**
     * Write a string colorcoded as header to output
     * @param String $message
     */
    public function header($message)
    {
        OutputWriter::write($message, OutputWriter::TYPE_HEADING);
    }

    /**
     * write a string colorcoded as positive/success to output
     * @param String $message
     */
    public function positive($message)
    {
        OutputWriter::write($message, OutputWriter::TYPE_POSITIVE);
    }

}
