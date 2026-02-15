<?php

namespace Dysback\Ogo\Logger;

abstract class BaseLogger implements ILogger
{
    abstract public function log($message, string $logLevel, string $category): void;
}
