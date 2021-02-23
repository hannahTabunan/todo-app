<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0646bb6cda4a81928c48cbbd64943f70
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/back/app',
        ),
    );

    public static $classMap = array (
        'App\\config\\Database' => __DIR__ . '/../..' . '/back/app/config/Database.php',
        'App\\objects\\Task' => __DIR__ . '/../..' . '/back/app/objects/Task.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0646bb6cda4a81928c48cbbd64943f70::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0646bb6cda4a81928c48cbbd64943f70::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0646bb6cda4a81928c48cbbd64943f70::$classMap;

        }, null, ClassLoader::class);
    }
}
