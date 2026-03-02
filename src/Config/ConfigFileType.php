<?php

namespace Dysback\Ogo\Config;

/**
 * Enum for config file types. (PHP, JSON, YAML, YML, DIR)
 */
enum ConfigFileType: string
{
    case PHP = 'php';
    case JSON = 'json';
    case YAML = 'yaml';
    case YML = 'yml';
    case DIR = '*';
}
