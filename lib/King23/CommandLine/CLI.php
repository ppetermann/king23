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

use King23\Core\Interfaces\Singleton;

/**
 * Utility Class for CLI tools
 * @package King23\CommandLine
 */
final class CLI implements Singleton
{
    private static $myInstance = null;

    private function __construct()
    {
    }

    /**
     * @return \King23\CommandLine\CLI
     */
    public static function getInstance()
    {
        if (is_null(self::$myInstance)) {
            self::$myInstance = new CLI();
        }
        return self::$myInstance;
    }

    /**
     * Write a string as regular message to output
     *
     * @param String $message
     */
    public function message($message = "")
    {
        OutputWriter::writeln($message);
    }

    /**
     * Write a string colorcoded as error to the output
     *
     * @param String $message
     */
    public function error($message)
    {
        OutputWriter::writeln($message, OutputWriter::TYPE_ERROR);
    }

    /**
     * Write a string colorcoded as warning to the output
     *
     * @param String $message
     */
    public function warning($message)
    {
        OutputWriter::writeln($message, OutputWriter::TYPE_WARNING);
    }

    /**
     * Write a string colorcoded as header to output
     *
     * @param String $message
     */
    public function header($message)
    {
        OutputWriter::writeln($message, OutputWriter::TYPE_HEADING);
    }

    /**
     * write a string colorcoded as positive/success to output
     *
     * @param String $message
     */
    public function positive($message)
    {
        OutputWriter::writeln($message, OutputWriter::TYPE_POSITIVE);
    }


    /**
     * @param $question
     * @return bool|string
     */
    public function ask($question)
    {
        $question .= "> ";
        OutputWriter::write($question, OutputWriter::TYPE_REGULAR);

        return InputReader::readln();
    }

    /**
     * @param string $question
     * @param array $answers
     * @return bool|string
     */
    public function askForAnswer($question, $answers)
    {
        $question .= ' (' . join(', ', $answers) . ')';
        while (true) {
            $result = $this->ask($question);

            // acceptable answer found
            if (in_array($result, $answers)) {
                return $result;
            }
        }
    }

    /**
     * Confirm question through stdin
     * @param string $question
     * @param bool $default
     * @return bool
     */
    public function confirm($question, $default = true)
    {
        $answers = array(
            "y",
            "n",
            "yes",
            "no",
            ""
        );

        if ($default) {
            $question .= " (" . Colors::COLOR_FGL_WHITE . "Y". Colors::COLOR_FG_DEFAULT ."/n)";
        } else {
            $question .= " (y/" . Colors::COLOR_FGL_WHITE . "N". Colors::COLOR_FG_DEFAULT .")";
        }

        while (true) {
            $result = strtolower($this->ask($question));

            // acceptable answer found
            if (in_array($result, $answers)) {

                $confirmation = in_array($result, array("y", "yes", "Yes", "YES"))
                    || (empty($result) && ("" === $result) === $default);

                if ($confirmation) {
                    return true;
                }
                return false;
            }
        }
    }
}
