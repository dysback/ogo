<?php

namespace Dysback\Ogo\Logger;

use DateTime;
use Dysback\Ogo\App;

class FileLogger extends BaseLogger implements ILogger
{
    private static ?FileLogger $instance = null;
    private string $logFilePath;
    private string $logLevel;
    private string $application;

    public function __construct(App $app)
    {
        $this->logFilePath = $app->config->get('LOGGER.LOG_FILE_PATH');
        $this->logLevel = $app->config->get('LOGGER.LOG_LEVEL');
        $this->application = $app->config->get('APPLICATION_NAME');
        echo print_r("FileLogger: " . $this->logFilePath . " " . $this->logLevel . " " . $this->application, true);
        $this->log("Logger initiated, Log level " . $this->logLevel, LogLevel::DEBUG, 'core');
    }

    public function log($message, string $logLevel = LogLevel::INFO, string $category = "user"): void
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
            . sprintf(" [%8s|%8s|%8s] %s\n", $logLevel, $this->application, $category, $data);
        $filename = $this->logFilePath . date('Y-m-d') . '.log';
        file_put_contents($filename, $row, FILE_APPEND);
    }
}
