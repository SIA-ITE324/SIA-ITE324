<?php

// Valid PHP Version?
$minPHPVersion = '8.1';
if (version_compare(PHP_VERSION, $minPHPVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPHPVersion,
        PHP_VERSION
    );

    exit($message);
}

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Define the APPPATH constant
define('APPPATH', FCPATH . 'app' . DIRECTORY_SEPARATOR);

// Define the ROOTPATH constant
define('ROOTPATH', FCPATH);

// Ensure the current directory is pointing to the front controller's directory
chdir(FCPATH);

// Load the paths config file
require APPPATH . 'Config/Paths.php';
$paths = new Config\Paths();

// Location of the framework bootstrap file.
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

// Load environment settings from .env files into $_SERVER and $_ENV
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

// Define ENVIRONMENT
if (! defined('ENVIRONMENT')) {
    define('ENVIRONMENT', env('CI_ENVIRONMENT', 'production'));
}

// Run the application
$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

$response = $app->run();
$response->send();
