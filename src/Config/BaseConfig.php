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

    protected function load(array $config): void
    {
        $this->config = array_merge($this->config, $config);
    }
}
