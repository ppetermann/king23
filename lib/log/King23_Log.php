<?php
/**
 * Class used for printing out / sending lines to bot
 */
class King23_Log
{
    /**
     * write out message for logging purposes
     * @param String $message
     * @param boolean $bot if set to true, info will be written to bot txt file aswell
     */
    public function log($message)
    {
        $finalizedMessage = "[". date("Y-m-d H:i:s"). "] " . $message . "\n";
        echo $finalizedMessage;
    }
}
