<?php

namespace Dysback\Ogo\Config;

/**
 * Class for config path object containing the path and the type of the config file.
 */
class ConfigPath
{
    public function __construct(public readonly string $path, public readonly ConfigFileType $type)
    {
    }
}
