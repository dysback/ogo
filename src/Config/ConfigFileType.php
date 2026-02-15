<?php

namespace Dysback\Ogo\Config;

enum ConfigFileType: string
{
    case PHP = 'php';
    case JSON = 'json';
    case YAML = 'yaml';
    case YML = 'yml';
    case DIR = '*';
}
