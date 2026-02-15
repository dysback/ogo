<?php

namespace Dysback\Ogo\Logger;

interface ILogger
{
    public function log($message, string $logLevel, string $category): void;
}
