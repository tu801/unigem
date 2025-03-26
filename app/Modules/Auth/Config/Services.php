<?php namespace Modules\Auth\Config;

use CodeIgniter\Model;
use Modules\Auth\Config\Auth as AuthConfig;
use Modules\Acp\Models\User\UserModel;
use Modules\Auth\Models\LoginModel;
use Modules\Auth\Authorization\GroupModel;
use Modules\Auth\Authorization\PermissionModel;
use Modules\Auth\Authorization\FlatAuthorization;
use Modules\Auth\Authentication\Passwords\PasswordValidator;
use Modules\Auth\Authentication\Activators\ActivatorInterface;
use Modules\Auth\Authentication\Activators\UserActivator;
use Modules\Auth\Authentication\Resetters\EmailResetter;
use Modules\Auth\Authentication\Resetters\ResetterInterface;
use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function authentication(string $lib = 'local', ?Model $userModel = null, ?Model $loginModel = null, bool $getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('authentication', $lib, $userModel, $loginModel);
        }

        $userModel ??= model(UserModel::class);
        $loginModel ??= model(LoginModel::class);

        /** @var AuthConfig $config */
        $config   = config('Auth');
        $class    = $config->authenticationLibs[$lib];
        $instance = new $class($config);

        return $instance
            ->setUserModel($userModel)
            ->setLoginModel($loginModel);
    }

    public static function authorization(?Model $groupModel = null, ?Model $permissionModel = null, ?Model $userModel = null, bool $getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('authorization', $groupModel, $permissionModel, $userModel);
        }

        $groupModel ??= model(GroupModel::class);
        $permissionModel ??= model(PermissionModel::class);
        $userModel ??= model(UserModel::class);

        $instance = new FlatAuthorization($groupModel, $permissionModel); // @phpstan-ignore-line

        return $instance->setUserModel($userModel); // @phpstan-ignore-line
    }

    /**
     * Returns an instance of the PasswordValidator.
     */
    public static function passwords(?AuthConfig $config = null, bool $getShared = true): PasswordValidator
    {
        if ($getShared) {
            return self::getSharedInstance('passwords', $config);
        }

        return new PasswordValidator($config ?? config(AuthConfig::class));
    }

    /**
     * Returns an instance of the activator.
     *
     * @param null $config
     * @param bool $getShared
     *
     * @return mixed|Activator
     */
    public static function activator(?AuthConfig $config = null, bool $getShared = true): ActivatorInterface
    {
        if ($getShared) {
            return self::getSharedInstance('activator', $config);
        }

        $config ??= config(AuthConfig::class);
        $class = $config->requireActivation ?? UserActivator::class;

        /** @var class-string<ActivatorInterface> $class */
        return new $class($config);
    }

    /**
     * Returns an instance of the Resetter.
     */
    public static function resetter(?AuthConfig $config = null, bool $getShared = true): ResetterInterface
    {
        if ($getShared) {
            return self::getSharedInstance('resetter', $config);
        }

        $config ??= config(AuthConfig::class);
        $class = $config->activeResetter ?? EmailResetter::class;

        /** @var class-string<ResetterInterface> $class */
        return new $class($config);
    }
}
