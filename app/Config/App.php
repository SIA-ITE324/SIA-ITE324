<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\FileHandler;

class App extends BaseConfig
{
    public string $baseURL = 'http://localhost:8080/';
    public string $indexPage = 'index.php';
    public string $appTimezone = 'UTC';
    public string $defaultLocale = 'en';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['en'];
    public string $cookiePrefix = '';
    public string $cookieDomain = '';
    public string $cookiePath = '/';
    public bool $cookieSecure = false;
    public bool $cookieHTTPOnly = false;
    public string $cookieSameSite = 'Lax';
    public bool $proxyIP = false;
    public array $proxyIPs = [];
    public bool $CSRFProtection = true;
    public string $CSRFTokenName = 'csrf_fleur_token';
    public string $CSRFCookieName = 'csrf_fleur_cookie';
    public int $CSRFExpire = 7200;
    public bool $CSRFRegenerate = true;
    public bool $CSRFStrict = true;
    public bool $CSPEnabled = false;

    /**
     * --------------------------------------------------------------------------
     * Global Content Security Policy
     * --------------------------------------------------------------------------
     *
     * Content Security Policy is a mechanism for controlling resources the
     * user agent can load for a given page, helping to mitigate attacks like
     * Cross Site Scripting.
     *
     * See: https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP
     *
     * These settings will be applied globally unless you restrict them to a
     * specific namespace.
     */
    public bool $CSPReportOnly = false;
    public array $CSPDefaultSrc = [];
    public array $CSPScriptSrc = [];
    public array $CSPStyleSrc = [];
    public array $CSPImgSrc = [];
    public array $CSPFontSrc = [];
    public array $CSPConnectSrc = [];
    public array $CSPMediaSrc = [];
    public array $CSPObjectSrc = [];
    public array $CSPBaseUri = [];
    public array $CSPFormAction = [];
    public array $CSPFrameAncestors = [];
    public array $CSPFrameSrc = [];
    public array $CSPNonce = [];
    public bool $CSPUpgradeInsecure = false;
}
