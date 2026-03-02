<?php

namespace Dysback\Ogo\Config;

/**
 * Interface for all config classes.
 * @package Dysback\Ogo\Config
 */
interface IConfig
{
    /**
     * Get a config value. (use dot(.) notation to access nested values)
     * @param string $congigPath The path to the config value.
     * @param mixed $default The default value if the config value is not found.
     * @return mixed The config value.
     */
    public function get(string $congigPath, $default = null);

    /**
     * Load config data from a PHP file.
     * @param string $filePath The path to the PHP file.
     * @return void
     */
    public function loadFromPhpFile(string $filePath): void;
    /**
     * Load config data from a JSON file.
     * @param string $filePath The path to the JSON file.
     * @return void
     */
    public function loadFromJsonFile(string $filePath): void;

    /**
     * Load config data from a YAML file.
     * @param string $filePath The path to the YAML file.
     * @return void
     */
    public function loadFromYamlFile(string $filePath): void;

    /**
     * Load config data from a directory containing multiple PHP, JSON, YAML or XML config files.
     * @param string $directoryPath The path to the directory.
     * @return void
     */
    public function loadFromDirectory(string $directoryPath): void;

    /**
     * Load config data from environment variables starting with the given prefix.
     * @param string $prefix The prefix to use for the environment variables.
     * @return void
     * Example: $config->loadFromEnvironmentVariables('APP_') will load config data
     * from environment variables starting with 'APP_' prefix.
     */
    public function loadFromEnvironmentVariables(string $prefix): void;
}
