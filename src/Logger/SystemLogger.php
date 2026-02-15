<?php

namespace Dysback\Ogo\Logger;

class SystemLogger implements ILogger
{
    private static $instance;


    private function setLogLevel($logLevel)
    {
    }


    public static function getLogger()
    {
        if (static::$instance) {
        }

        return static::$instance;
    }


    public function log($message, string $logLevel = LogLevel::INFO, string $category = 'user'): void
    {
        throw new \Exception('Not implemented yet');
    }
}
