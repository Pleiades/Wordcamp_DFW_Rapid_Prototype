<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit19d2b756a6ac5df2e1dfb8bb95356f38
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Faker\\' => 6,
            'FakerRestaurant\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Faker\\' => 
        array (
            0 => __DIR__ . '/..' . '/fzaninotto/faker/src/Faker',
        ),
        'FakerRestaurant\\' => 
        array (
            0 => __DIR__ . '/..' . '/jzonta/faker-restaurant/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit19d2b756a6ac5df2e1dfb8bb95356f38::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit19d2b756a6ac5df2e1dfb8bb95356f38::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}