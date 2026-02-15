<?php

namespace Dysback\Ogo;

class App
{
    public readonly array $configPaths;
    private static ?App $instance = null;
    public private(set) readonly Config\IConfig $config;
    public private(set) readonly Logger\ILogger $logger;
    public private(set) readonly Router\IRouter $router;
    //public private(set) Database\IDatabase $Database;
    //public private(set) Cache\ICache $Cache;
    public private(set) array $others = [];

    public static function initialize(array $configPaths): App
    {
        self::$instance = new self();
        self::$instance->configPaths = $configPaths;
        return self::$instance;
    }

    private function __clone(): void
    {
        throw new \Exception('Cannot clone singleton');
    }

    public function __wakeup(): void
    {
        throw new \Exception('Cannot unserialize singleton');
    }

    public static function getInstance(): App
    {
        try {
            return self::$instance;
        } catch (\Exception $e) {
            throw new \Exception('App not initialized: ' . $e->getMessage());
        }
    }

    public function setConfig(Config\IConfig $config): void
    {
        $this->config = $config;
    }

    public function setLogger(Logger\ILogger $logger): void
    {
        $this->logger = $logger;
    }

    public function setRouter(Router\IRouter $router): void
    {
        $this->router = $router;
    }
}
