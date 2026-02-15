<?php

namespace Dysback\Ogo\Logger;

use DateTime;

class FileLogger extends BaseLogger implements ILogger
{
    private static ?FileLogger $instance = null;
    private string $baseFilePath;
    private string $logLevel;
    private string $application;

    private function __construct(string $logPath, string $application, string $logLevel = LogLevel::INFO)
    {
        $this->baseFilePath = $logPath;
        $this->logLevel = $logLevel;
        $this->application = $application;
        $this->log("Logger initiated, Log level " . $logLevel, LogLevel::DEBUG, 'core');
    }

    private function __clone()
    {
        throw new \Exception("Cannot clone singleton");
    }

    public static function getInstance(string $logPath, string $application, string $logLevel = LogLevel::INFO)
    {
        if (self::$instance === null) {
            self::$instance = new self($logPath, $application, $logLevel);
        }
        return self::$instance;
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
        $filename = $this->baseFilePath . date('Y-m-d') . '.log';
        file_put_contents($filename, $row, FILE_APPEND);
    }
}
