<?php
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


    public function message($message ="")
    {
        King23_CLI_OutputWriter::write($message);
    }

    public function error($message)
    {
        King23_CLI_OutputWriter::write($message, King23_CLI_OutputWriter::TYPE_Error);
    }

    public function warning($message)
    {
        King23_CLI_OutputWriter::write($message, King23_CLI_OutputWriter::TYPE_Warning);
    }

    public function header($message)
    {
        King23_CLI_OutputWriter::write($message, King23_CLI_OutputWriter::TYPE_Heading);
    }


}
