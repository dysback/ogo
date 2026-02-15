<?php

namespace Dysback\Ogo\Config;

interface IConfig
{
    public function get(string $congigPath, $default = null);

    public function loadFromPhpFile(string $filePath): void;

    public function loadFromJsonFile(string $filePath): void;

    public function loadFromYamlFile(string $filePath): void;

    public function loadFromDirectory(string $directoryPath): void;

    public function loadFromEnvironmentVariables(string $prefix): void;
}
