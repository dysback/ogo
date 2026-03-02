<?php

namespace Dysback\Ogo\Config;

use Dysback\Ogo\App;

/**
 * Base class for all config classes.
 */
abstract class BaseConfig implements IConfig
{
    protected readonly App $app;
    public protected(set) array $config = [];

    /**
     * Get a config value. (use dot(.) notation to access nested values)
     * @param string $key
     * @param mixed $default The default value if the config value is not found.
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }

    /**
     * Merge config data into the config array.
     * @param array $config The config data to load.
     * @return void
     */
    protected function load(array $config): void
    {
        $this->config = $this->arrayMergeRecursive($this->config, $config);
    }

    /**
     * Merge two arrays recursively.
     * @param array $array1 The first array to merge.
     * @param array $array2 The second array to merge.
     * @return array The merged array.
     */
    protected function arrayMergeRecursive(array $array1, array $array2): array
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
