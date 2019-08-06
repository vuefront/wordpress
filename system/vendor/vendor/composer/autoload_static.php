<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfa0230d8e21c898fbf12851c8a7c5359
{
    public static $files = array (
        'c594688b3441835d5575f3085da4a242' => __DIR__ . '/..' . '/webonyx/graphql-php/src/deprecated.php',
    );

    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'GraphQL\\' => 8,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'GraphQL\\' => 
        array (
            0 => __DIR__ . '/..' . '/webonyx/graphql-php/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfa0230d8e21c898fbf12851c8a7c5359::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfa0230d8e21c898fbf12851c8a7c5359::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}