<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Configures aliases for Filter Classes to make reading things nicer
     * and simpler.
     *
     * @var array<string, string>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'auth'          => \App\Filters\AuthFilter::class,
        'admin'         => \App\Filters\AdminFilter::class,
        'guest'         => \App\Filters\GuestFilter::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, string>>
     */
    public array $globals = [
        'before' => [
            'csrf' => ['except' => 'api/*'],
            'honeypot',
            'secureheaders',
        ],
        'after' => [
            'toolbar',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * @var array<string, array<string, string>>
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI pattern.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array<string, array<string, string>>
     */
    public array $filters = [
        'auth' => [
            'before' => [
                'admin/*',
                'customer/*',
                'staff/*'
            ]
        ],
        'guest' => [
            'before' => [
                'login',
                'register'
            ]
        ]
    ];
}
