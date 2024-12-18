<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb86a4fdeb5615dc9780834e0a358ea6d
{
    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'Yondu\\Couchbase\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Yondu\\Couchbase\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitb86a4fdeb5615dc9780834e0a358ea6d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb86a4fdeb5615dc9780834e0a358ea6d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb86a4fdeb5615dc9780834e0a358ea6d::$classMap;

        }, null, ClassLoader::class);
    }
}
