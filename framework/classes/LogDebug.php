<?php
class LogDebug
{
    //that maybe need to some change or what? there is terrible to make it here as it?
    private static $logs_directory='/var/www/html/bot/www/logs/';
    
    public static function log_msg($message)
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }
    
        self::_log_write('[INFO] ' . $message);
    }
    
    public static function log_error($message)
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }
    
         self::_log_write('[ERROR] ' . $message);
    }
    
    private static function _log_write($message)
    {
        $trace = debug_backtrace();
        $function_name = isset($trace[2]) ? $trace[2]['function'] : '-';
        $mark = date("H:i:s") . ' [' . $function_name . ']';
        $log_name = self::$logs_directory.'log_' . date("j.n.Y") . '.log';
        file_put_contents($log_name, $mark . " : " . $message . "\n", FILE_APPEND);
    }
    
    public static function log($string)
    {
        echo ($string."<br> \n");
    }
}