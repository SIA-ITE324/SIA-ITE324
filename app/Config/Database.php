<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds your migrations and seeds directories.
     */
    public string $filesPath = APPPATH . 'Database/';

    /**
     * Lets you set which connection group would
     * be used by default. The name must match
     * one of the connection groups defined below.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     */
    public array $default = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'fleur_db',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'cacheOn'  => false,
        'cacheDir' => '',
        'charset'  => 'utf8mb4',
        'DBCollat' => 'utf8mb4_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    /**
     * This database connection is used when
     * running PHPUnit database tests.
     */
    public array $tests = [
        'DSN'      => '',
        'hostname' => '127.0.0.1',
        'username' => '',
        'password' => '',
        'database' => ':memory:',
        'DBDriver' => 'SQLite3',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'cacheOn'  => false,
        'cacheDir' => '',
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];
}
