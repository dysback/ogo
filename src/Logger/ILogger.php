<?php

namespace Dysback\Ogo\Logger;

/**
 * Interface for all loggers.
 * @package Dysback\Ogo\Logger
 */
interface ILogger
{
    /**
     * Log a message.
     * @param mixed $message The message to log.
     * @param LogLevel $logLevel The log level. (DEBUG, INFO, WARNING, ERROR, CRITICAL)
     * @param string $category The category of the log. (e.g. 'core', 'router', 'database', 'cache', 'utility', 'etc.')
     * @return void
     * Example: $logger->log("Hello, world!", LogLevel::DEBUG, 'core'); will log the message "Hello, world!"
     * with the log level DEBUG to the category 'core' if the log level is DEBUG or higher. If the log level is
     * WARNING or higher, the message will not be logged.
     */
    public function log(mixed $message, LogLevel $logLevel, string $category): void;
}
