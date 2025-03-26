<?php

namespace Config;

use App\Filters\GroupPermission;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use Modules\Auth\Filters\AdminFilter;
use Modules\Auth\Filters\LoginFilter;
use Modules\Auth\Filters\PermissionFilter;
use Modules\Auth\Filters\RoleFilter;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, string>
     * @phpstan-var array<string, class-string>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'login'         => LoginFilter::class,
        'role'          => RoleFilter::class,
        'permission'    => PermissionFilter::class,
        'admin'         => AdminFilter::class,
        'userGroupPer'  => GroupPermission::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, array<string>>
     * @phpstan-var array<string, list<string>>|array<string, array<string, array<string, string>>>
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
             'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
             'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     */
    public array $filters = [];

    /**
     * Filters constructor.
     */
    public function __construct()
    {
        $this->filters['login'] = ['before' => [ADMIN_AREA, ADMIN_AREA.'/*']];
        $this->filters['admin'] = ['before' => [ADMIN_AREA, ADMIN_AREA.'/*']];
        $this->filters['userGroupPer'] = ['before' => [ADMIN_AREA, ADMIN_AREA.'/*']];
    }
}
