<?php

namespace Dysback\Ogo\Config;

class ConfigPath
{
    public function __construct(public readonly string $path, public readonly ConfigFileType $type)
    {
    }
}
