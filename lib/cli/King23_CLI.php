<?php
/**
 * conveniance class for output in the King23 CLI
 */
final class King23_CLI implements King23_Singleton
{
    private static $myInstance =null;

    private function  __construct()
    {
    }

    /**
     * @return KING23_CLI
     */
    public static function getInstance()
    {
        if(is_null(self::$myInstance))
            self::$myInstance = new King23_CLI();
        return self::$myInstance;
    }

    /**
     * Write a string as regular message to output
     * @param String $message
     */
    public function message($message ="")
    {
        King23_CLI_OutputWriter::write($message);
    }

    /**
     * Write a string colorcoded as error to the output
     * @param String $message
     */
    public function error($message)
    {
        King23_CLI_OutputWriter::write($message, King23_CLI_OutputWriter::TYPE_Error);
    }

    /**
     * Write a string colorcoded as warning to the output
     * @param String $message
     */
    public function warning($message)
    {
        King23_CLI_OutputWriter::write($message, King23_CLI_OutputWriter::TYPE_Warning);
    }

    /**
     * Write a string colorcoded as header to output
     * @param String $message
     */
    public function header($message)
    {
        King23_CLI_OutputWriter::write($message, King23_CLI_OutputWriter::TYPE_Heading);
    }

    /**
     * write a string colorcoded as positive/success to output
     * @param String $message
     */
    public function positive($message)
    {
        King23_CLI_OutputWriter::write($message, King23_CLI_OutputWriter::TYPE_Positive);
    }

}
