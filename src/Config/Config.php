<?php

namespace Dysback\Ogo\Config;

use Dysback\Ogo\App;

/**
 * Config class.
 */
class Config extends BaseConfig implements IConfig
{
    public function __construct(App $app)
    {
        $this->app = $app;
        foreach ($app->configPaths as $configPath) {
            if ($configPath instanceof ConfigPath) {
                $type = $configPath->type;
                $path = $configPath->path;
            } elseif (is_string($configPath)) {
                $type = $this->getFileTypeFromPath($configPath);
                $path = $configPath;
            } else {
                throw new \Exception("Invalid config path: {$configPath}");
            }

            switch ($type) {
                case ConfigFileType::PHP:
                    $this->loadFromPhpFile($path);
                    break;
                case ConfigFileType::JSON:
                    $this->loadFromJsonFile($path);
                    break;
                case ConfigFileType::YAML:
                case ConfigFileType::YML:
                    $this->loadFromYamlFile($path);
                    break;
                case ConfigFileType::DIR:
                    $this->loadFromDirectory($path);
            }
        }
    }

    private function getFileTypeFromPath(string $path): ConfigFileType
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'php':
                return ConfigFileType::PHP;
            case 'json':
                return ConfigFileType::JSON;
            case 'yaml':
            case 'yml':
                return ConfigFileType::YAML;
            default:
                return ConfigFileType::DIR;
        }
    }

    public function loadFromPhpFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Config file not found: {$filePath}");
        }

        $config = require $filePath;
        if (!is_array($config)) {
            throw new \Exception("Config file must return an array: {$filePath}");
        }
        $this->load($config);
    }

    public function loadFromJsonFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Config file not found: {$filePath}");
        }
        $config = json_decode(file_get_contents($filePath), true);
        if (!is_array($config)) {
            throw new \Exception("Config file must return an array: {$filePath}");
        }
        $this->load($config);
    }

    public function loadFromYamlFile(string $filePath): void
    {
        if (!function_exists('yaml_parse_file')) {
            throw new \Exception("YAML PHP extension not installed. Cannot load YAML config: {$filePath}");
        }

        if (!file_exists($filePath)) {
            throw new \Exception("Config file not found: {$filePath}");
        }

        $config = yaml_parse_file($filePath);
        if (!is_array($config)) {
            throw new \Exception("Config file must return an array: {$filePath}");
        }
        $this->load($config);
    }

    /**
     * Load config from environment variables.
     *
     * @param string $prefix The prefix to use for the environment variables
     */
    public function loadFromEnvironmentVariables(string $prefix): void
    {
        $config = [];
        foreach ($_ENV as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $config[str_replace($prefix, '', $key)] = $value;
            }
        }
    }

    /**
     * Load config from a directory.
     *
     * @param string $directoryPath The path to the directory
     *
     * @throws \Exception If the directory is not found
     *                    Files are loaded in the following order:
     *                    1. PHP files (with .php extension)
     *                    2. JSON files (with .json extension)
     *                    3. YAML files (with .yaml or .yml extension)
     * @throws \Exception If the directory is not found
     * @throws \Exception If the directory is not a directory
     */
    public function loadFromDirectory(string $directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            throw new \Exception("Config directory not found: {$directoryPath}");
        }

        $files = glob($directoryPath . '/*.php');
        $files = array_merge($files, glob($directoryPath . '/*.json'));
        $files = array_merge($files, glob($directoryPath . '/*.yaml'));
        $files = array_merge($files, glob($directoryPath . '/*.yml'));

        foreach ($files as $file) {
            if ('php' === pathinfo($file, PATHINFO_EXTENSION)) {
                $this->loadFromPhpFile($file);
            } elseif ('json' === pathinfo($file, PATHINFO_EXTENSION)) {
                $this->loadFromJsonFile($file);
            } elseif ('yaml' === pathinfo($file, PATHINFO_EXTENSION)) {
                $this->loadFromYamlFile($file);
            } elseif ('yml' === pathinfo($file, PATHINFO_EXTENSION)) {
                $this->loadFromYamlFile($file);
            }
        }
    }

    public function merge(array $config): void
    {
        $this->config = $this->arrayMergeRecursive($this->config, $config);
    }

    public function toJson(): string
    {
        return json_encode($this->config, JSON_PRETTY_PRINT);
    }

    public function getSection(string $section): array
    {
        return $this->get($section, []);
    }

    private function arrayMergeRecursive(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMergeRecursive($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
