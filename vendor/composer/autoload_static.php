<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7f1eb741758147fb18057c5b8218eef0
{
    public static $files = array (
        'efcff227298589a0e1d27035c99ac6c0' => __DIR__ . '/../..' . '/config/database.php',
    );

    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7f1eb741758147fb18057c5b8218eef0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7f1eb741758147fb18057c5b8218eef0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7f1eb741758147fb18057c5b8218eef0::$classMap;

        }, null, ClassLoader::class);
    }
}
