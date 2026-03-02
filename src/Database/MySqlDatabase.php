<?php

declare(strict_types=1);

namespace Dysback\Ogo\Database;

use Dysback\Ogo\App;

/**
 * MySQL database connection implementation backed by PDO.
 *
 * Reads connection parameters from the application config under the DATABASE key:
 *   DATABASE.HOST     — hostname (default: localhost)
 *   DATABASE.PORT     — port number (default: 3306)
 *   DATABASE.NAME     — database name
 *   DATABASE.USER     — database user
 *   DATABASE.PASSWORD — database password
 *   DATABASE.CHARSET  — character set (default: utf8mb4)
 */
class MySqlDatabase extends BaseDatabase implements IDatabase
{
    /**
     * Creates a PDO connection using values from the application config.
     *
     * @param App $app The application instance used to read config values.
     */
    public function __construct(App $app)
    {
        $host = $app->config->get('DATABASE.HOST', 'localhost');
        $port = $app->config->get('DATABASE.PORT', 3306);
        $name = $app->config->get('DATABASE.NAME');
        $user = $app->config->get('DATABASE.USER');
        $password = $app->config->get('DATABASE.PASSWORD');
        $charset = $app->config->get('DATABASE.CHARSET', 'utf8mb4');

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

        $this->pdo = new \PDO($dsn, $user, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
