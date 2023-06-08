<?php

namespace julio101290\Auth\Config;

use CodeIgniter\Model;
use Config\Services as BaseService;
use julio101290\Auth\Authentication\Activators\ActivatorInterface;
use julio101290\Auth\Authentication\Activators\UserActivator;
use julio101290\Auth\Authentication\Passwords\PasswordValidator;
use julio101290\Auth\Authentication\Resetters\EmailResetter;
use julio101290\Auth\Authentication\Resetters\ResetterInterface;
use julio101290\Auth\Authorization\FlatAuthorization;
use julio101290\Auth\Authorization\GroupModel;
use julio101290\Auth\Authorization\PermissionModel;
use julio101290\Auth\Config\Auth as AuthConfig;
use julio101290\Auth\Models\LoginModel;
use julio101290\Auth\Models\UserModel;

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

    // Note that these input models *must be* of types GroupModel, PermissionModel, and UserModel respectively
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
     * Returns an instance of the Activator.
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
