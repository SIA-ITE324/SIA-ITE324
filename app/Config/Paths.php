<?php

namespace Config;

class Paths
{
    /**
     * ---------------------------------------------------------------
     * SYSTEM FOLDER NAME
     * ---------------------------------------------------------------
     *
     * This variable must contain the name of your "system" folder.
     * Include the path if the folder is not in the same  directory
     * as this file.
     */
    public $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system';

    /**
     * ---------------------------------------------------------------
     * APPLICATION FOLDER NAME
     * ---------------------------------------------------------------
     *
     * If you want this front controller to use a different "app"
     * folder than the default one you can set its name here. The folder
     * can also be renamed or relocated anywhere on your server. If
     * you do, use a full server path. For more info please see the
     * user guide:
     * https://codeigniter.com/user_guide/general/managing_apps.html
     *
     * NO TRAILING SLASH!
     */
    public $appDirectory = __DIR__ . '/..';

    /**
     * ---------------------------------------------------------------
     * WRITABLE DIRECTORY NAME
     * ---------------------------------------------------------------
     *
     * This variable must contain the name of your "writable" directory.
     * The writable directory contains uploaded files, cache data,
     * session data, etc.
     */
    public $writableDirectory = __DIR__ . '/../../writable';

    /**
     * ---------------------------------------------------------------
     * TESTS DIRECTORY NAME
     * ---------------------------------------------------------------
     *
     * This variable must contain the name of your "tests" directory.
     */
    public $testsDirectory = __DIR__ . '/../../tests';

    /**
     * ---------------------------------------------------------------
     * VIEW DIRECTORY NAME
     * ---------------------------------------------------------------
     *
     * This variable must contain the name of the directory that
     * contains the view files used by your application. By default
     * this is the same as the appDirectory, but you can place them
     * anywhere you like as long as you update this variable.
     */
    public $viewDirectory = __DIR__ . '/../Views';

    /**
     * ---------------------------------------------------------------
     * HOOK DIRECTORY NAME
     * ---------------------------------------------------------------
     *
     * This variable must contain the name of your "hooks" directory.
     */
    public $hooksDirectory = __DIR__ . '/../../hooks';

    /**
     * ---------------------------------------------------------------
     * COMMAND DIRECTORY NAME
     * ---------------------------------------------------------------
     *
     * This variable must contain the name of your "commands" directory.
     */
    public $commandDirectory = __DIR__ . '/../../app/Commands';
}
