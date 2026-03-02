<?php

namespace Dysback\Ogo\Logger;

use DateTime;
use Dysback\Ogo\App;

/**
 * File logger class.
 * @package Dysback\Ogo\Logger
 */
class FileLogger extends BaseLogger implements ILogger
{
    private static ?FileLogger $instance = null;
    private string $logFilePath;
    private LogLevel $logLevel;
    private string $application;

    public function __construct(App $app)
    {
        $this->logFilePath = $app->config->get('LOGGER.LOG_FILE_PATH');
        $this->logLevel = LogLevel::from($app->config->get('LOGGER.LOG_LEVEL'));
        $this->application = $app->config->get('APPLICATION_NAME');
        $this->log("Logger initiated, Log level " . $this->logLevel->value, LogLevel::DEBUG, 'core');
    }

    /**
     * Log a message to the file.
     * @param mixed $message The message to log.
     * @param LogLevel $logLevel The log level. (DEBUG, INFO, WARNING, ERROR, CRITICAL)
     * @param string $category The category of the log. (e.g. 'core', 'router', 'database', 'cache', 'utility', 'etc.')
     * @return void
     * Example: $logger->log("Hello, world!", LogLevel::DEBUG, 'core'); will log the message "Hello, world!"
     * with the log level DEBUG to the category 'core' if the log level is DEBUG or higher. If the log level is
     * WARNING or higher, the message will not be logged.
     */
    public function log(mixed $message, LogLevel $logLevel = LogLevel::INFO, string $category = "user"): void
    {
        if (!LogLevel::shouldLogA($logLevel, $this->logLevel)) {
            return;
        }
        if (!is_scalar($message)) {
            $data = json_encode($message);
        } else {
            $data = $message;
        }
        $time = new DateTime();

        $row =  $time->format('H:i:s.u')
            . sprintf(" [%8s|%8s|%8s] %s\n", $logLevel->value, $this->application, $category, $data);
        $filename = $this->logFilePath . date('Y-m-d') . '.log';
        file_put_contents($filename, $row, FILE_APPEND);
    }
}
