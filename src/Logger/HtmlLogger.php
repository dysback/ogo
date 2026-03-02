<?php

namespace Dysback\Ogo\Logger;

/**
 * Html logger class. NOT IMPLEMENTED FOR NOW.
 * @package Dysback\Ogo\Logger
 */
class HtmlLogger extends BaseLogger implements ILogger
{
    public function log(mixed $message, LogLevel $logLevel, string $category): void
    {
        throw new \Exception('Not implemented');
    }
}
